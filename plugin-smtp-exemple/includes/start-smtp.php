<?php
class Plugin_SMTP_Starter {
    public static function init() {
        // Vérifie que l'utilisateur a les droits d'administration
        if (!current_user_can('manage_options')) {
            self::log_and_die('Tentative d\'acces non autorise a l\'init SMTP par l\'utilisateur ID: ' . get_current_user_id(), __('Accès refusé.', 'plugin-smtp-exemple'));
        }
        $token = Token_Manager::getToken();
        if (!is_array($token) || !isset($token['access_token'])) {
            self::log_and_die('Token OAuth absent ou invalide pour l\'utilisateur ID: ' . get_current_user_id(), __('Authentification OAuth requise.', 'plugin-smtp-exemple') . ' <a href="' . esc_url(Plugin_SMTP_OAuthHandler::buildAuthUrl()) . '">' . __('Autoriser', 'plugin-smtp-exemple') . '</a>');
        }
    }

    private static function log_and_die($log_message, $display_message) {
        error_log($log_message);
        wp_die($display_message);
    }
}