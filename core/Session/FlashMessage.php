<?php

namespace Core\Session;

/**
 * Gestion des messages flash pour l'application
 */
class FlashMessage
{
    private const FLASH_KEY = 'flash_messages';
    private const TYPES = ['success', 'error', 'warning', 'info'];

    /**
     * Ajoute un message flash
     * 
     * @param string $type Type du message (success, error, warning, info)
     * @param string $message Contenu du message
     */
    public static function add(string $type, string $message): void
    {
        if (!in_array($type, self::TYPES)) {
            $type = 'info';
        }

        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }

        $_SESSION[self::FLASH_KEY][] = [
            'type' => $type,
            'message' => $message,
            'displayed' => false,
            'timestamp' => time()
        ];
    }

    /**
     * Récupère tous les messages non affichés
     */
    public static function getAll(): array
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return [];
        }

        $messages = [];
        foreach (self::TYPES as $type) {
            $typeMessages = self::get($type);
            if (!empty($typeMessages)) {
                $messages[$type] = $typeMessages;
            }
        }

        return $messages;
    }

    /**
     * Récupère les messages d'un type spécifique
     */
    public static function get(string $type): array
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return [];
        }

        $messages = array_filter($_SESSION[self::FLASH_KEY], function($message) use ($type) {
            return $message['type'] === $type && !$message['displayed'];
        });

        // Marquer ces messages comme affichés
        self::markAsDisplayed($type);

        return array_column($messages, 'message');
    }

    /**
     * Vérifie s'il y a des messages d'un type spécifique
     */
    public static function has(string $type = null): bool
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return false;
        }

        if ($type === null) {
            return !empty(array_filter($_SESSION[self::FLASH_KEY], fn($m) => !$m['displayed']));
        }

        return !empty(array_filter(
            $_SESSION[self::FLASH_KEY],
            fn($m) => $m['type'] === $type && !$m['displayed']
        ));
    }

    /**
     * Marque les messages comme affichés
     */
    private static function markAsDisplayed(string $type = null): void
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return;
        }

        foreach ($_SESSION[self::FLASH_KEY] as &$message) {
            if ($type === null || $message['type'] === $type) {
                $message['displayed'] = true;
            }
        }
    }

    /**
     * Nettoie les messages affichés
     */
    public static function clear(string $type = null): void
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return;
        }

        if ($type === null) {
            $_SESSION[self::FLASH_KEY] = [];
            return;
        }

        $_SESSION[self::FLASH_KEY] = array_filter(
            $_SESSION[self::FLASH_KEY],
            fn($m) => $m['type'] !== $type
        );
    }

    /**
     * Ajoute des messages de différents types
     */
    public static function success(string $message): void
    {
        self::add('success', $message);
    }

    public static function error(string $message): void
    {
        self::add('error', $message);
    }

    public static function warning(string $message): void
    {
        self::add('warning', $message);
    }

    public static function info(string $message): void
    {
        self::add('info', $message);
    }

    /**
     * Nettoie les vieux messages (plus de 5 minutes)
     */
    public static function cleanup(): void
    {
        if (!isset($_SESSION[self::FLASH_KEY])) {
            return;
        }

        $fiveMinutesAgo = time() - 300;
        $_SESSION[self::FLASH_KEY] = array_filter(
            $_SESSION[self::FLASH_KEY],
            fn($m) => $m['timestamp'] > $fiveMinutesAgo
        );
    }
} 