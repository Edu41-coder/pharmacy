<?php

namespace App\Service\Util;

class StringHelper
{
    public static function slugify(string $text): string
    {
        // Convertir en minuscules
        $text = mb_strtolower($text);
        
        // Remplacer les caractères accentués
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        
        // Supprimer tout sauf lettres, chiffres et tirets
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        
        // Supprimer les tirets multiples
        $text = preg_replace('/-+/', '-', $text);
        
        // Supprimer les tirets au début et à la fin
        return trim($text, '-');
    }

    public static function truncate(string $text, int $length): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        $truncated = mb_substr($text, 0, $length);
        
        // Éviter de couper un mot
        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }
        
        return $truncated . '...';
    }

    public static function sanitize(string $text): string
    {
        // Supprimer les balises HTML
        $text = strip_tags($text);
        
        // Convertir les caractères spéciaux en entités HTML
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
} 