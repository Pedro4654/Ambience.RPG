<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ConsoleTVs\Profanity\Facades\Profanity;

class ModerationController extends Controller
{
    public function moderate(Request $request)
    {
        $request->validate(['text' => 'required|string']);
        $text = $request->input('text');

        $builder = Profanity::blocker($text);

        $has = false;
        $badWords = [];
        $cleaned = $text;

        if (method_exists($builder, 'hasBadWords')) {
            $has = (bool) $builder->hasBadWords();
            $badWords = $builder->badWords() ?? [];
            $cleaned = $builder->filter();
        } else {
            // fallback
            try {
                $cleaned = $builder->filter();
                if (method_exists($builder, 'clean')) {
                    $has = ! $builder->clean();
                } else {
                    // tentativa best-effort comparando tokens e dicionário
                    $dict = [];
                    try { $dict = Profanity::dictionary() ?: []; } catch (\Throwable $e) { $dict = []; }
                    $tokens = preg_split('/\s+/', mb_strtolower($text));
                    foreach ($tokens as $t) {
                        $tok = preg_replace('/[^\p{L}\p{N}]+/u', '', $t);
                        if (!$tok) continue;
                        if (in_array($tok, $dict)) {
                            $badWords[] = $tok;
                            $has = true;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // se der merda, assume false
                $has = false;
                $badWords = [];
                $cleaned = $text;
            }
        }

        return response()->json([
            'inappropriate' => (bool) $has,
            'matched' => array_values($badWords),
            'cleaned' => $cleaned,
            'original' => $text,
        ]);
    }
}
