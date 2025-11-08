<?php

/**
 * File Helper - app/Helpers/FileHelper.php
 * Funções auxiliares para manipulação de arquivos
 */

namespace App\Helpers;

class FileHelper {
    
    /**
     * Gera thumbnail ou preview para um arquivo
     */
    public static function getFilePreview($tipo, $arquivo) {
        if (!$arquivo) {
            return self::getIconForType('outros');
        }
        
        switch($tipo) {
            case 'imagem':
                return asset('storage/' . $arquivo->caminho_arquivo);
            case 'video':
                return self::getVideoThumbnail($arquivo);
            case 'modelo_3d':
                return self::get3DModelThumbnail($arquivo);
            default:
                return self::getIconForType($tipo);
        }
    }
    
    /**
     * Retorna ícone para tipo de arquivo
     */
    public static function getIconForType($tipo) {
        $icons = [
            'imagem' => '🖼️',
            'video' => '🎬',
            'modelo_3d' => '🎮',
            'ficha' => '📋',
            'audio' => '🎵',
            'pdf' => '📄',
            'arquivo' => '📦',
            'outros' => '📦'
        ];
        
        return $icons[$tipo] ?? '📦';
    }
    
    /**
     * Retorna string de tipo legível
     */
    public static function getTipoLegivel($tipo) {
        $tipos = [
            'imagem' => 'Imagem',
            'video' => 'Vídeo',
            'modelo_3d' => 'Modelo 3D',
            'ficha' => 'Ficha RPG',
            'audio' => 'Áudio',
            'pdf' => 'PDF',
            'arquivo' => 'Arquivo',
            'outros' => 'Outro'
        ];
        
        return $tipos[$tipo] ?? 'Arquivo';
    }
    
    /**
     * Gera thumbnail para vídeo
     */
    private static function getVideoThumbnail($arquivo) {
        // Retorna um placeholder para vídeo
        return 'video'; // Usar no JavaScript
    }
    
    /**
     * Gera thumbnail para modelo 3D
     */
    private static function get3DModelThumbnail($arquivo) {
        // Retorna um placeholder para 3D
        return 'modelo_3d'; // Usar no JavaScript
    }
    
    /**
     * Verifica se arquivo é 3D (.glb ou .gltf)
     */
    public static function is3DModel($caminho) {
        $ext = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
        return in_array($ext, ['glb', 'gltf']);
    }
    
    /**
     * Verifica se arquivo é vídeo
     */
    public static function isVideo($tipo_mime) {
        return strpos($tipo_mime, 'video/') === 0;
    }
    
    /**
     * Verifica se arquivo é imagem
     */
    public static function isImage($tipo_mime) {
        return strpos($tipo_mime, 'image/') === 0;
    }
}