<?php
// S'assurer que le chemin du plugin est défini
if (!defined('PLUGIN_SMTP_PLUGIN_PATH')) {
    define('PLUGIN_SMTP_PLUGIN_PATH', dirname(dirname(__FILE__)) . '/');
}

// Inclure les fonctions WordPress si nécessaire
if (!function_exists('wp_mail')) {
    require_once(ABSPATH . 'wp-includes/pluggable.php');
}
if (!function_exists('sanitize_email')) {
    require_once(ABSPATH . 'wp-includes/formatting.php');
}
if (!function_exists('is_email')) {
    require_once(ABSPATH . 'wp-includes/formatting.php');
}
if (!function_exists('esc_html')) {
    require_once(ABSPATH . 'wp-includes/formatting.php');
}

class Plugin_SMTP_Admin {
    public static function registerAdminMenu() {
        add_menu_page(
                'Plugin SMTP OAuth',
                'Plugin SMTP OAuth',
            'manage_options',
            'plugin-smtp-exemple-oauth',
            [self::class, 'renderAdminPage'],
            'dashicons-email',
            26
        );
    }

    public static function renderAdminPage() {
        try {
            $token = class_exists('Token_Manager') ? Token_Manager::getToken() : null;
            if (!class_exists('Plugin_SMTP_OAuthHandler')) {
                echo '<div class="notice notice-error"><p>Erreur : La classe Plugin_SMTP_OAuthHandler est manquante.</p></div>';
                return;
            }
            // Traitement du formulaire d'envoi de mail de test
            if (isset($_POST['plugin_action']) && $_POST['plugin_action'] === 'send_test_email') {
                $test_email = isset($_POST['test_email']) ? sanitize_email($_POST['test_email']) : '';
                if (is_email($test_email)) {
                    $subject = 'Test SMTP Exemple';
                    $message = 'Ceci est un test d\'envoi via Plugin SMTP Exemple.';
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    if (wp_mail($test_email, $subject, $message, $headers)) {
                        echo '<div class="notice notice-success"><p>Email de test envoyé à ' . esc_html($test_email) . '.</p></div>';
                    } else {
                        echo '<div class="notice notice-error"><p>Echec de l\'envoi de l\'email de test.</p></div>';
                    }
                } else {
                    echo '<div class="notice notice-error"><p>Adresse email invalide.</p></div>';
                }
            }
                include PLUGIN_SMTP_PLUGIN_PATH . 'templates/config-page.php';
        } catch (Throwable $e) {
            echo '<div class="notice notice-error"><p>Erreur fatale : ' . esc_html($e->getMessage()) . '</p></div>';
        }
    }
}

// Ajout du hook pour afficher le menu admin
    add_action('admin_menu', [Plugin_SMTP_Admin::class, 'registerAdminMenu']);