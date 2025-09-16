<?php
class Plugin_SMTP_Mailer {
    public static function configureMailer($phpmailer) {
    $token = Token_Manager::getToken();
        if ($token && isset($token['access_token'])) {
            $phpmailer->isSMTP();
            // Utilise les constantes définies dans wp-config.php
            $phpmailer->Host = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.example.com';
            $phpmailer->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;
            $phpmailer->SMTPAuth = true;
            $phpmailer->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'tls';

            $phpmailer->AuthType = 'XOAUTH2';
            $phpmailer->oauthUserEmail = defined('OAUTH_USER_EMAIL') ? OAUTH_USER_EMAIL : get_option('admin_email');
            $phpmailer->oauthClientId = defined('OAUTH_CLIENT_ID') ? OAUTH_CLIENT_ID : '';
            $phpmailer->oauthClientSecret = defined('OAUTH_CLIENT_SECRET') ? OAUTH_CLIENT_SECRET : '';
            $phpmailer->oauthRefreshToken = $token['refresh_token'] ?? '';
            $phpmailer->oauthAccessToken = $token['access_token'];
        }
    }
}