<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\SavedPost;
use App\Models\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


/**
 * PostController - Atualizado com Busca
 */
class PostController extends Controller {
    
    use AuthorizesRequests;
    
    /**
     * Feed principal
     */
    public function index(Request $request) {
        $page = $request->get('page', 1);
        $per_page = 12;
        
        $posts = Post::with([
            'autor:id,username,avatar_url',
            'arquivos',
            'curtidas',
            'comentarios.autor'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate($per_page);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $posts,
                'total' => $posts->total(),
                'per_page' => $per_page
            ]);
        }
        
        return view('comunidade.feed', ['posts' => $posts]);
    }
    
    /**
     * Buscar postagens por título ou conteúdo
     */
    public function buscar(Request $request) {
        $termo = $request->get('q', '');
        $tipo = $request->get('tipo', null);
        
        $query = Post::with(['autor', 'arquivos']);
        
        // Busca por título ou conteúdo
        if ($termo) {
            $query->where(function($q) use ($termo) {
                $q->where('titulo', 'LIKE', '%' . $termo . '%')
                  ->orWhere('conteudo', 'LIKE', '%' . $termo . '%');
            });
        }
        
        // Filtro por tipo
        if ($tipo && in_array($tipo, ['texto', 'imagem', 'video', 'modelo_3d', 'ficha', 'outros'])) {
            $query->where('tipo_conteudo', $tipo);
        }
        
        $posts = $query->orderBy('created_at', 'desc')->paginate(12);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'posts' => $posts,
                'total' => $posts->total(),
                'termo' => $termo
            ]);
        }
        
        return view('comunidade.search', [
            'posts' => $posts,
            'termo' => $termo,
            'tipo' => $tipo
        ]);
    }
    
    /**
     * Exibir formulário de criação
     */
    public function create() {
        return view('comunidade.create');
    }
    
    /**
     * Criar nova postagem
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'conteudo' => 'required|string|max:5000',
            'tipo_conteudo' => 'required|in:texto,imagem,video,modelo_3d,ficha,outros',
            'arquivos.*' => 'nullable|file|max:50000'
        ]);
        
        try {
            $post = Post::create([
                'usuario_id' => Auth::id(),
                'titulo' => $validated['titulo'],
                'conteudo' => $validated['conteudo'],
                'tipo_conteudo' => $validated['tipo_conteudo'],
                'slug' => Str::slug($validated['titulo']) . '-' . uniqid()
            ]);
            
            // Processar arquivos
            if ($request->hasFile('arquivos')) {
                foreach ($request->file('arquivos') as $index => $arquivo) {
                    $this->salvar_arquivo($post, $arquivo, $index);
                }
            }
            
            $post->load(['autor', 'arquivos']);
            
            Log::info('Postagem criada', [
                'post_id' => $post->id,
                'usuario_id' => Auth::id(),
                'titulo' => $post->titulo
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Postagem criada com sucesso!',
                    'post' => $post
                ], 201);
            }
            
            return redirect()->route('comunidade.post.show', $post->slug)
                ->with('success', 'Postagem criada com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao criar postagem', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar postagem'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Erro ao criar postagem']);
        }
    }
    
    /**
     * Visualizar postagem
     */
    public function show($slug) {
        $post = Post::where('slug', $slug)
            ->with([
                'autor:id,username,avatar_url',
                'arquivos',
                'curtidas',
                'comentarios.autor',
                'comentarios.respostas.autor'
            ])
            ->firstOrFail();
        
        $curtido = false;
        $salvo = false;
        
        if (Auth::check()) {
            $curtido = $post->curtido_por_usuario(Auth::id());
            $salvo = $post->salvo_por_usuario(Auth::id());
        }
        
        // Incrementar visualizações
        $post->increment('visualizacoes');
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'post' => $post,
                'curtido' => $curtido,
                'salvo' => $salvo
            ]);
        }
        
        return view('comunidade.show', [
            'post' => $post,
            'curtido' => $curtido,
            'salvo' => $salvo
        ]);
    }
    
    /**
     * Editar postagem
     */
    public function edit($id) {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        return view('comunidade.edit', ['post' => $post]);
    }
    
    /**
     * Atualizar postagem
     */
    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'conteudo' => 'required|string|max:5000',
            'tipo_conteudo' => 'required|in:texto,imagem,video,modelo_3d,ficha,outros'
        ]);
        
        $post->update($validated);
        
        Log::info('Postagem atualizada', [
            'post_id' => $post->id,
            'usuario_id' => Auth::id()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postagem atualizada!',
                'post' => $post
            ]);
        }
        
        return redirect()->route('comunidade.post.show', $post->slug)
            ->with('success', 'Postagem atualizada!');
    }
    
    /**
     * Deletar postagem
     */
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        $post->delete();
        
        Log::info('Postagem deletada', [
            'post_id' => $post->id,
            'usuario_id' => Auth::id()
        ]);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postagem deletada!'
            ]);
        }
        
        return redirect()->route('comunidade.feed')
            ->with('success', 'Postagem deletada!');
    }
    
    /**
     * Salvar arquivo
     */
    private function salvar_arquivo(Post $post, $arquivo, $index) {
        $nome_original = $arquivo->getClientOriginalName();
        $tipo_mime = $arquivo->getMimeType();
        $tamanho = $arquivo->getSize();
        
        // Determinar tipo
        $tipo = 'arquivo';
        if (str_starts_with($tipo_mime, 'image/')) {
            $tipo = 'imagem';
        } elseif (str_starts_with($tipo_mime, 'video/')) {
            $tipo = 'video';
        } elseif (str_ends_with($nome_original, '.glb') || str_ends_with($nome_original, '.gltf')) {
            $tipo = 'modelo_3d';
        }
        
        // Salvar arquivo
        $nome_arquivo = 'posts/' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('posts', $arquivo, basename($nome_arquivo));
        
        // Salvar no banco
        PostFile::create([
            'post_id' => $post->id,
            'nome_arquivo' => $nome_original,
            'caminho_arquivo' => $nome_arquivo,
            'tipo_mime' => $tipo_mime,
            'tamanho' => $tamanho,
            'tipo' => $tipo,
            'ordem' => $index
        ]);
    }
}