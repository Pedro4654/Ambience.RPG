<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\SavedPost;
use App\Models\PostFile;
use App\Models\FichaRpg;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
            'comentarios.autor',
            'fichaRpg'
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
        
        $query = Post::with(['autor', 'arquivos', 'fichaRpg']);
        
        if ($termo) {
            $query->where(function($q) use ($termo) {
                $q->where('titulo', 'LIKE', '%' . $termo . '%')
                  ->orWhere('conteudo', 'LIKE', '%' . $termo . '%');
            });
        }
        
        if ($tipo && in_array($tipo, ['texto', 'imagem', 'video', 'ficha_rpg', 'outros'])) {
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
     * Criar nova postagem (COM FICHA RPG E MODERAÇÃO)
     */
    public function store(Request $request) {
        // ========== VALIDAÇÃO BASE ==========
        $rules = [
            'titulo' => 'required|string|max:200',
            'conteudo' => 'required|string|max:5000',
            'tipo_conteudo' => 'required|in:texto,imagem,video,ficha_rpg,outros',
            'arquivos.*' => 'nullable|file|max:50000',
        ];
        
        // ========== VALIDAÇÃO ESPECÍFICA PARA FICHA RPG ==========
        if ($request->input('tipo_conteudo') === 'ficha_rpg') {
            $rules = array_merge($rules, [
                'ficha_nome' => 'required|string|max:100',
                'ficha_nivel' => 'nullable|integer|min:1|max:100',
                'ficha_raca' => 'nullable|string|max:50',
                'ficha_classe' => 'nullable|string|max:50',
                'ficha_foto' => 'nullable|image|max:5000',
                'ficha_forca' => 'nullable|integer|min:1|max:20',
                'ficha_agilidade' => 'nullable|integer|min:1|max:20',
                'ficha_vigor' => 'nullable|integer|min:1|max:20',
                'ficha_inteligencia' => 'nullable|integer|min:1|max:20',
                'ficha_sabedoria' => 'nullable|integer|min:1|max:20',
                'ficha_carisma' => 'nullable|integer|min:1|max:20',
                'ficha_habilidades' => 'nullable|string|max:2000',
                'ficha_historico' => 'nullable|string|max:3000',
            ]);
        }
        
        $validated = $request->validate($rules);
        
        // ========== MODERAÇÃO DE TEXTO ==========
        try {
            $moderacaoTitulo = $this->moderarTexto($validated['titulo']);
            if ($moderacaoTitulo && $moderacaoTitulo['inappropriate']) {
                Log::warning('Post bloqueado: título inapropriado', [
                    'usuario_id' => Auth::id(),
                    'titulo' => $validated['titulo']
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O título contém conteúdo inapropriado e foi bloqueado.',
                        'field' => 'titulo'
                    ], 422);
                }
                
                return back()->withErrors([
                    'titulo' => 'O título contém conteúdo inapropriado.'
                ])->withInput();
            }
            
            $moderacaoConteudo = $this->moderarTexto($validated['conteudo']);
            if ($moderacaoConteudo && $moderacaoConteudo['inappropriate']) {
                Log::warning('Post bloqueado: conteúdo inapropriado', [
                    'usuario_id' => Auth::id(),
                    'conteudo_preview' => substr($validated['conteudo'], 0, 100)
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O conteúdo contém linguagem inapropriada e foi bloqueado.',
                        'field' => 'conteudo'
                    ], 422);
                }
                
                return back()->withErrors([
                    'conteudo' => 'O conteúdo contém linguagem inapropriada.'
                ])->withInput();
            }
            
            // Moderar campos da ficha se for ficha_rpg
            if ($validated['tipo_conteudo'] === 'ficha_rpg') {
                if (!empty($validated['ficha_nome'])) {
                    $moderacaoNome = $this->moderarTexto($validated['ficha_nome']);
                    if ($moderacaoNome && $moderacaoNome['inappropriate']) {
                        return back()->withErrors([
                            'ficha_nome' => 'Nome do personagem contém conteúdo inapropriado.'
                        ])->withInput();
                    }
                }
                
                if (!empty($validated['ficha_habilidades'])) {
                    $moderacaoHab = $this->moderarTexto($validated['ficha_habilidades']);
                    if ($moderacaoHab && $moderacaoHab['inappropriate']) {
                        return back()->withErrors([
                            'ficha_habilidades' => 'Habilidades contêm conteúdo inapropriado.'
                        ])->withInput();
                    }
                }
                
                if (!empty($validated['ficha_historico'])) {
                    $moderacaoHist = $this->moderarTexto($validated['ficha_historico']);
                    if ($moderacaoHist && $moderacaoHist['inappropriate']) {
                        return back()->withErrors([
                            'ficha_historico' => 'Histórico contém conteúdo inapropriado.'
                        ])->withInput();
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Erro na moderação de texto do post', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);
        }
        
        // ========== VALIDAÇÃO DE IMAGENS/VÍDEOS ==========
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $tamanho = $arquivo->getSize();
                if ($tamanho > 50 * 1024 * 1024) {
                    Log::warning('Post bloqueado: arquivo muito grande', [
                        'usuario_id' => Auth::id(),
                        'tamanho' => $tamanho,
                        'nome' => $arquivo->getClientOriginalName()
                    ]);
                    
                    return back()->withErrors([
                        'arquivos' => 'Arquivo muito grande. Máximo: 50MB'
                    ])->withInput();
                }
                
                $extensao = strtolower($arquivo->getClientOriginalExtension());
                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'mov'];
                
                if (!in_array($extensao, $extensoesPermitidas)) {
                    Log::warning('Post bloqueado: extensão não permitida', [
                        'usuario_id' => Auth::id(),
                        'extensao' => $extensao,
                        'nome' => $arquivo->getClientOriginalName()
                    ]);
                    
                    return back()->withErrors([
                        'arquivos' => 'Tipo de arquivo não permitido.'
                    ])->withInput();
                }
            }
        }
        
        try {
            // ========== CRIAR POST ==========
            $post = Post::create([
                'usuario_id' => Auth::id(),
                'titulo' => $validated['titulo'],
                'conteudo' => $validated['conteudo'],
                'tipo_conteudo' => $validated['tipo_conteudo'],
                'slug' => Str::slug($validated['titulo']) . '-' . uniqid()
            ]);
            
            // ========== PROCESSAR FICHA RPG ==========
            if ($validated['tipo_conteudo'] === 'ficha_rpg') {
                $fichaData = [
                    'post_id' => $post->id,
                    'nome' => $validated['ficha_nome'],
                    'nivel' => $request->input('ficha_nivel', 1),
                    'raca' => $request->input('ficha_raca'),
                    'classe' => $request->input('ficha_classe'),
                    'forca' => $request->input('ficha_forca', 10),
                    'agilidade' => $request->input('ficha_agilidade', 10),
                    'vigor' => $request->input('ficha_vigor', 10),
                    'inteligencia' => $request->input('ficha_inteligencia', 10),
                    'sabedoria' => $request->input('ficha_sabedoria', 10),
                    'carisma' => $request->input('ficha_carisma', 10),
                    'habilidades' => $request->input('ficha_habilidades'),
                    'historico' => $request->input('ficha_historico'),
                ];
                
                // Salvar foto da ficha se houver
                if ($request->hasFile('ficha_foto')) {
                    $foto = $request->file('ficha_foto');
                    $nomeArquivo = 'ficha_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $caminhoFoto = $foto->storeAs('fichas', $nomeArquivo, 'public');
                    $fichaData['foto_url'] = $caminhoFoto;
                }
                
                // Criar registro da ficha
                FichaRpg::create($fichaData);
                
                Log::info('Ficha RPG criada', [
                    'post_id' => $post->id,
                    'nome_personagem' => $fichaData['nome']
                ]);
            }
            
            // ========== PROCESSAR ARQUIVOS NORMAIS (IMAGEM, VÍDEO, OUTROS) ==========
            if ($request->hasFile('arquivos')) {
                foreach ($request->file('arquivos') as $index => $arquivo) {
                    $this->salvar_arquivo($post, $arquivo, $index);
                }
            }
            
            $post->load(['autor', 'arquivos', 'fichaRpg']);
            
            Log::info('Postagem criada com sucesso', [
                'post_id' => $post->id,
                'usuario_id' => Auth::id(),
                'titulo' => $post->titulo,
                'tipo_conteudo' => $post->tipo_conteudo,
                'tem_ficha' => $validated['tipo_conteudo'] === 'ficha_rpg'
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
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar postagem: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Erro ao criar postagem: ' . $e->getMessage()])->withInput();
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
                'fichaRpg'
            ])
            ->firstOrFail();
        
        $curtido = false;
        $salvo = false;
        
        if (Auth::check()) {
            $curtido = $post->curtido_por_usuario(Auth::id());
            $salvo = $post->salvo_por_usuario(Auth::id());
        }
        
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
     * Atualizar postagem (COM MODERAÇÃO)
     */
    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'conteudo' => 'required|string|max:5000',
            'tipo_conteudo' => 'required|in:texto,imagem,video,ficha_rpg,outros',
            'arquivos.*' => 'nullable|file|max:50000'
        ]);
        
        // ========== MODERAÇÃO DE TEXTO ==========
        try {
            $moderacaoTitulo = $this->moderarTexto($validated['titulo']);
            if ($moderacaoTitulo && $moderacaoTitulo['inappropriate']) {
                Log::warning('Atualização bloqueada: título inapropriado', [
                    'usuario_id' => Auth::id(),
                    'post_id' => $post->id,
                    'titulo' => $validated['titulo']
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O título contém conteúdo inapropriado.',
                        'field' => 'titulo'
                    ], 422);
                }
                return back()->withErrors(['titulo' => 'O título contém conteúdo inapropriado.'])->withInput();
            }
            
            $moderacaoConteudo = $this->moderarTexto($validated['conteudo']);
            if ($moderacaoConteudo && $moderacaoConteudo['inappropriate']) {
                Log::warning('Atualização bloqueada: conteúdo inapropriado', [
                    'usuario_id' => Auth::id(),
                    'post_id' => $post->id
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O conteúdo contém linguagem inapropriada.',
                        'field' => 'conteudo'
                    ], 422);
                }
                return back()->withErrors(['conteudo' => 'O conteúdo contém linguagem inapropriada.'])->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erro na moderação ao atualizar post', [
                'error' => $e->getMessage(),
                'post_id' => $post->id
            ]);
        }
        
        // ========== VALIDAÇÃO DE NOVOS ARQUIVOS ==========
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $tamanho = $arquivo->getSize();
                if ($tamanho > 50 * 1024 * 1024) {
                    return back()->withErrors([
                        'arquivos' => 'Arquivo muito grande. Máximo: 50MB'
                    ])->withInput();
                }
                
                $extensao = strtolower($arquivo->getClientOriginalExtension());
                $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'mov'];
                
                if (!in_array($extensao, $extensoesPermitidas)) {
                    return back()->withErrors([
                        'arquivos' => 'Tipo de arquivo não permitido.'
                    ])->withInput();
                }
            }
        }
        
        $post->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'tipo_conteudo' => $validated['tipo_conteudo']
        ]);
        
        // Processar novos arquivos se houver
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $index => $arquivo) {
                $this->salvar_arquivo($post, $arquivo, $index + $post->arquivos->count());
            }
        }
        
        Log::info('Postagem atualizada', [
            'post_id' => $post->id,
            'usuario_id' => Auth::id(),
            'titulo' => $post->titulo
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postagem atualizada!',
                'post' => $post->load(['autor', 'arquivos', 'fichaRpg'])
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
        
        // Deletar arquivos do storage
        foreach ($post->arquivos as $arquivo) {
            if (Storage::disk('public')->exists($arquivo->caminho_arquivo)) {
                Storage::disk('public')->delete($arquivo->caminho_arquivo);
            }
        }
        
        // Deletar foto da ficha se existir
        if ($post->fichaRpg && $post->fichaRpg->getOriginal('foto_url')) {
            $fotoPath = $post->fichaRpg->getOriginal('foto_url');
            if (Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
        }
        
        $post->delete();
        
        Log::info('Postagem deletada', [
            'post_id' => $post->id,
            'usuario_id' => Auth::id(),
            'titulo' => $post->titulo
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
        try {
            $nome_original = $arquivo->getClientOriginalName();
            $tipo_mime = $arquivo->getMimeType();
            $tamanho = $arquivo->getSize();
            
            $tipo = 'arquivo';
            if (str_starts_with($tipo_mime, 'image/')) {
                $tipo = 'imagem';
            } elseif (str_starts_with($tipo_mime, 'video/')) {
                $tipo = 'video';
            }
            
            $extensao = $arquivo->getClientOriginalExtension();
            $nome_unico = uniqid() . '_' . time() . '.' . $extensao;
            $caminho_completo = 'posts/' . $nome_unico;
            
            // Salvar arquivo
            Storage::disk('public')->putFileAs('posts', $arquivo, $nome_unico);
            
            // Criar registro no banco
            PostFile::create([
                'post_id' => $post->id,
                'nome_arquivo' => $nome_original,
                'caminho_arquivo' => $caminho_completo,
                'tipo_mime' => $tipo_mime,
                'tamanho' => $tamanho,
                'tipo' => $tipo,
                'ordem' => $index
            ]);
            
            Log::info('Arquivo salvo', [
                'post_id' => $post->id,
                'arquivo' => $nome_original,
                'tipo' => $tipo,
                'tamanho' => $tamanho
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar arquivo', [
                'post_id' => $post->id,
                'arquivo' => $arquivo->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Moderar texto via endpoint /moderate
     */
    private function moderarTexto($texto) {
        if (empty($texto)) return null;
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->post(url('/moderate'), [
                'text' => $texto
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::debug('Moderação de texto', [
                    'texto_length' => strlen($texto),
                    'inappropriate' => $data['inappropriate'] ?? false
                ]);
                return $data;
            }
            
            Log::warning('Moderação falhou', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Erro ao chamar endpoint de moderação', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}