<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModerationController extends Controller
{
    /**
     * Lista de palavras ofensivas customizadas (mesmo do JS)
     */
    /**
 * Lista de palavras ofensivas POR CATEGORIA
 */
private function getBadWordsCategories()
{
    return [
        // Palavras de baixo calão (profanity) - permitidas para 15+
        'profanity' => [
            'porra', 'merda', 'merdao', 'bosta', 'cacete', 'carai', 'caraio', 
            'caralha', 'caralho', 'caralhudo', 'viado', 'viadinho', 'gay',
            'otario', 'babaca', 'retardado'
        ],
        
        // Conteúdo sexual (sexual/porn) - bloqueado para menores de 18
        'sexual' => [
            'buceta', 'bucetao', 'bucetas', 'bucetasso', 'bucetinha', 'bucetinhas',
            'boceta', 'periquita', 'xota', 'xotinha', 'xoxota', 'xoxotas', 'xoxotinha',
            'xereca', 'xexeca', 'chereca', 'xebreca', 'prexeca', 'prencheca',
            'pau', 'pica', 'piroca', 'pirocao', 'pirocas', 'pirocudo', 'bilau',
            'penis', 'vagina', 'cuzinho', 'cuzao', 'cu', 'anus',
            'boquete', 'boqueteira', 'boqueteiro', 'boquetes', 'mamada',
            'chupada', 'chupador', 'chupadora', 'chupando', 'chupeta', 'chupetinha',
            'fudendo', 'fudido', 'trepar', 'trepada', 'trepadas', 'transar', 'transa', 'transando',
            'metendo', 'suruba', 'surubas', 'menage', 'menages',
            'gozada', 'gozadas', 'gozar', 'gozo', 'ejacular',
            'punheta', 'punheteiro', 'masturbar', 'masturbacao',
            'pornografia', 'porno', 'porn', 'xxx', 'sexo', 'sexual',
            'tesao', 'tezao', 'tezuda', 'tezudo', 'tesuda', 'tesudas', 'tesudo',
            'peituda', 'peitudas', 'peitos', 'seios', 'mamas',
            'rabuda', 'rabudas', 'bunda', 'bundao', 'rabo',
            'safada', 'safadas', 'safado', 'safados', 'putaria',
            'puta', 'putas', 'putinha', 'prostituta', 'prostituto',
            'estupro', 'estuprar', 'violacao', 'abuso sexual',
            'pedofilia', 'pedofilo', 'menor de idade'
        ]
    ];
}

private function getBadWordsList()
{
    $categories = $this->getBadWordsCategories();
    return array_merge($categories['profanity'], $categories['sexual']);
}

    /**
     * Normaliza texto removendo acentos
     */
    private function removeDiacritics($str)
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        return $str ?: $str;
    }

    /**
     * Aplica mapeamento leet speak
     */
    private function applyLeetMap($str)
    {
        $map = [
            '4' => 'a', '@' => 'a', '8' => 'b', '3' => 'e', '€' => 'e',
            '1' => 'i', '!' => 'i', '0' => 'o', '9' => 'g', '5' => 's',
            '$' => 's', '7' => 't', '+' => 't', '2' => 'z', '6' => 'g',
            '#' => 'h'
        ];
        
        return strtr(mb_strtolower($str), $map);
    }

    /**
     * Colapsa caracteres repetidos
     */
    private function collapseRepeats($str)
    {
        return preg_replace('/(.)\1{2,}/', '$1', $str);
    }

    /**
 * Verifica palavras e retorna com categorias
 */
private function checkBadWordsWithCategories($text)
{
    $categories = $this->getBadWordsCategories();
    $found = [
        'profanity' => [],
        'sexual' => []
    ];
    
    // Normalizar texto
    $normalized = mb_strtolower($text);
    $normalized = $this->removeDiacritics($normalized);
    $normalized = $this->applyLeetMap($normalized);
    $normalized = preg_replace('/[^a-z0-9\s]+/', ' ', $normalized);
    $normalized = $this->collapseRepeats($normalized);
    
    Log::info('[Moderation] Texto normalizado', [
        'original' => $text,
        'normalized' => $normalized
    ]);

    // Separar em palavras
    $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY);

    Log::info('[Moderation] Palavras detectadas', [
        'words' => $words
    ]);
    
    // Verificar cada categoria
    foreach ($categories as $category => $badWords) {
        foreach ($badWords as $badWord) {
            if (strlen($badWord) < 3) continue;
            
            // Normalizar palavra ofensiva
            $badWordNorm = mb_strtolower($badWord);
            $badWordNorm = $this->removeDiacritics($badWordNorm);
            $badWordNorm = $this->applyLeetMap($badWordNorm);
            $badWordNorm = preg_replace('/[^a-z0-9]+/', '', $badWordNorm);
            $badWordNorm = $this->collapseRepeats($badWordNorm);
            
            if (strlen($badWordNorm) < 3) continue;
            
            // Verificar se a palavra existe como palavra completa
            if (in_array($badWordNorm, $words)) {
                $found[$category][] = $badWord;
                continue;
            }
            
            // Para palavras maiores, verificar com word boundary
            if (strlen($badWordNorm) >= 4) {
                $pattern = '/\b' . preg_quote($badWordNorm, '/') . '\b/i';
                if (preg_match($pattern, $normalized)) {
                    $found[$category][] = $badWord;
                }
            }
        }
    }
    
    // Remover duplicatas
    $found['profanity'] = array_unique($found['profanity']);
    $found['sexual'] = array_unique($found['sexual']);
    
    return $found;
}

    /**
     * Limpa o texto substituindo palavras ofensivas
     */
    private function cleanText($text, $matches)
    {
        if (empty($matches)) {
            return $text;
        }
        
        $cleaned = $text;
        
        foreach ($matches as $badWord) {
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/i';
            $replacement = str_repeat('*', max(3, min(strlen($badWord), 10)));
            $cleaned = preg_replace($pattern, $replacement, $cleaned);
        }
        
        return $cleaned;
    }

    /**
 * Endpoint de moderação com categorias
 */
public function moderate(Request $request)
{
    $request->validate(['text' => 'required|string']);
    $text = $request->input('text');

    try {
        // Detectar palavras por categoria
        $categorized = $this->checkBadWordsWithCategories($text);
        
        // Determinar flags
        $flags = [];
        if (!empty($categorized['profanity'])) {
            $flags[] = 'profanity'; // ← Era 'profanity', estava correto
        }
        if (!empty($categorized['sexual'])) {
            $flags[] = 'sexual';
        }
        
        $inappropriate = !empty($flags);
        
        // Juntar todas as palavras detectadas
        $allMatches = array_merge($categorized['profanity'], $categorized['sexual']);
        
        // Limpar texto se necessário
        $cleaned = $inappropriate ? $this->cleanText($text, $allMatches) : $text;
        
        // Log apenas quando detectar algo
        if ($inappropriate) {
            Log::info('Moderação detectou conteúdo inapropriado', [
                'flags' => $flags,
                'profanity_count' => count($categorized['profanity']),
                'sexual_count' => count($categorized['sexual']),
                'text_length' => strlen($text),
                'ip' => $request->ip()
            ]);
        }

        return response()->json([
            'inappropriate' => $inappropriate,
            'matched' => array_values($allMatches),
            'flags' => $flags,  // ✅ ADICIONAR FLAGS CATEGORIZADAS
            'cleaned' => $cleaned,
            'original' => $text,
            'categories' => $categorized // ✅ DETALHES POR CATEGORIA
        ]);

    } catch (\Exception $e) {
        Log::error('Erro na moderação', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'inappropriate' => false,
            'matched' => [],
            'flags' => [],
            'cleaned' => $text,
            'original' => $text,
            'error' => 'Erro ao processar moderação'
        ]);
    }
}
}