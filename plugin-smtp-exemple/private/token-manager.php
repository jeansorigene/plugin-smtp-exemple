<?php
/**
 * Gestion des tokens OAuth pour le plugin exemple SMTP (stockage fichier sécurisé)
 */
class Token_Manager {
    const TOKEN_FILE = '/variable/private/token.json';

    // Sauvegarde le token dans le fichier sécurisé
    public static function saveToken($token) {
        if (is_array($token)) {
            file_put_contents(self::TOKEN_FILE, json_encode($token));
        }
    }

    // Récupère le token depuis le fichier sécurisé
    public static function getToken() {
        if (file_exists(self::TOKEN_FILE)) {
            $token = json_decode(file_get_contents(self::TOKEN_FILE), true);
            return is_array($token) ? $token : null;
        }
        return null;
    }

    // Supprime le token
    public static function deleteToken() {
        if (file_exists(self::TOKEN_FILE)) {
            unlink(self::TOKEN_FILE);
        }
    }
}