<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModerationController extends Controller
{
    /**
     * Lista de palavras ofensivas customizadas (mesmo do JS)
     */
    private function getBadWordsList()
    {
        return [
            "arrombada", "arrombadas", "arrombado", "babaca", "bacurinha", "baitola", 
            "bichona", "bixa", "boceta", "boiola", "bolcinha", "bolsinha", "boquete", 
            "boqueteira", "boqueteiro", "boquetera", "boquetero", "boquetes", "bosta", 
            "brecheca", "bucefula", "buceta", "bucetao", "bucetas", "bucetasso", 
            "bucetinha", "bucetinhas", "bucetonas", "cacete", "cachuleta", "cagalhao", 
            "carai", "caraio", "caralha", "caralho", "caralhudo", "cassete", "cequelada", 
            "cequelado", "chalerinha", "chatico", "chavasca", "checheca", "chereca", 
            "chibio", "chimbica", "chupada", "chupador", "chupadora", "chupando", 
            "chupeta", "chupetinha", "chupou", "porra", "crossdresser", "cuecao", 
            "custozinha", "cuzao", "cuzinho", "cuzinhos", "dadeira", "encoxada", 
            "enrabadas", "fornicada", "fudendo", "fudido", "furustreca", "gostozudas", 
            "gozada", "gozadas", "greludas", "gulosinha", "katchanga", "bilau", 
            "lesbofetiche", "lixa-pica", "mede-rola", "megasex", "mela-pentelho", 
            "meleca", "melequinha", "menage", "menages", "merda", "merdao", "meretriz", 
            "metendo", "mijada", "otario", "papa-duro", "pau", "pausudas", "pechereca", 
            "peidao", "peido", "peidorreiro", "peitos", "peituda", "peitudas", 
            "periquita", "pica", "piranhuda", "piriguetes", "piroca", "pirocao", 
            "pirocas", "pirocudo", "pitbitoca", "pitchbicha", "pitchbitoca", "pithbicha", 
            "pithbitoca", "pitibicha", "pitrica", "pixota", "prencheca", "prexeca", 
            "priquita", "priquito", "punheta", "punheteiro", "pussy", "puta", "putaria", 
            "putas", "putinha", "quenga", "rabuda", "rabudas", "rameira", "rapariga", 
            "retardado", "saca-rola", "safada", "safadas", "safado", "safados", 
            "sequelada", "sexboys", "sexgatas", "sirica", "siririca", "sotravesti", 
            "suruba", "surubas", "taioba", "tarada", "tchaca", "tcheca", "tchonga", 
            "tchuchuca", "tchutchuca", "tesuda", "tesudas", "tesudo", "tetinha", 
            "tezao", "tezuda", "tezudo", "tgatas", "t-girls", "tobinha", "tomba-macho", 
            "topsexy", "transa", "transando", "travecas", "traveco", "travecos", 
            "trepada", "trepadas", "vacilao", "vadjaina", "vadia", "vagabunda", 
            "vagabundo", "vaginismo", "vajoca", "veiaca", "veiaco", "viadinho", "viado", 
            "xabasca", "xana", "xaninha", "xatico", "xavasca", "xebreca", "xereca", 
            "xexeca", "xibio", "xoroca", "gay", "xota", "xotinha", "xoxota", "xoxotas", 
            "xoxotinha", "xulipa", "xumbrega", "xupaxota", "xupeta", "xupetinha"
        ];
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
     * Verifica se contém palavras ofensivas usando word boundaries
     */
    private function checkBadWords($text)
    {
        $badWords = $this->getBadWordsList();
        $matches = [];
        
        // Normalizar texto
        $normalized = mb_strtolower($text);
        $normalized = $this->removeDiacritics($normalized);
        $normalized = $this->applyLeetMap($normalized);
        $normalized = preg_replace('/[^a-z0-9\s]+/', ' ', $normalized);
        $normalized = $this->collapseRepeats($normalized);
        
        // Separar em palavras
        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($badWords as $badWord) {
            if (strlen($badWord) < 3) continue; // Ignorar palavras muito curtas
            
            // Normalizar palavra ofensiva
            $badWordNorm = mb_strtolower($badWord);
            $badWordNorm = $this->removeDiacritics($badWordNorm);
            $badWordNorm = $this->applyLeetMap($badWordNorm);
            $badWordNorm = preg_replace('/[^a-z0-9]+/', '', $badWordNorm);
            $badWordNorm = $this->collapseRepeats($badWordNorm);
            
            if (strlen($badWordNorm) < 3) continue;
            
            // Verificar se a palavra existe como palavra completa
            if (in_array($badWordNorm, $words)) {
                $matches[] = $badWord;
                continue;
            }
            
            // Para palavras maiores (>=4), verificar com word boundary
            if (strlen($badWordNorm) >= 4) {
                $pattern = '/\b' . preg_quote($badWordNorm, '/') . '\b/i';
                if (preg_match($pattern, $normalized)) {
                    $matches[] = $badWord;
                }
            }
        }
        
        return array_unique($matches);
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
     * Endpoint de moderação
     */
    public function moderate(Request $request)
    {
        $request->validate(['text' => 'required|string']);
        $text = $request->input('text');

        try {
            // Detectar palavras ofensivas
            $matches = $this->checkBadWords($text);
            $inappropriate = !empty($matches);
            
            // Limpar texto se necessário
            $cleaned = $inappropriate ? $this->cleanText($text, $matches) : $text;
            
            // Log apenas quando detectar algo
            if ($inappropriate) {
                Log::info('Moderação detectou conteúdo inapropriado', [
                    'matches' => $matches,
                    'text_length' => strlen($text),
                    'ip' => $request->ip()
                ]);
            }

            return response()->json([
                'inappropriate' => $inappropriate,
                'matched' => array_values($matches),
                'cleaned' => $cleaned,
                'original' => $text,
            ]);

        } catch (\Exception $e) {
            Log::error('Erro na moderação', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Em caso de erro, retorna como não inapropriado para não bloquear o usuário
            return response()->json([
                'inappropriate' => false,
                'matched' => [],
                'cleaned' => $text,
                'original' => $text,
                'error' => 'Erro ao processar moderação'
            ]);
        }
    }
}