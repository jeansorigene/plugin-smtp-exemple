<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Plugin_SMTP_Sender {
    public static function sendMail($to, $subject, $body, $headers = []) {
        // Sanitize inputs

        // Sanitize and validate recipient email
        $to = sanitize_email($to); // Nettoie l'adresse email
        if (!is_email($to)) { // Vérifie que l'email est valide
            return new WP_Error('invalid_email', 'Adresse email invalide.');
        }
        // Sanitize subject and body
        $subject = sanitize_text_field($subject); // Nettoie le sujet
        $body = wp_kses_post($body); // Nettoie le corps du message (HTML autorisé sécurisé)
        // Filter headers to allow only valid format
        $safe_headers = [];
        foreach ($headers as $header) {
            // Autorise uniquement le format 'Header-Name: value' pour éviter l'injection
            if (preg_match('/^[A-Za-z0-9\-]+: .+$/', $header)) {
                $safe_headers[] = $header;
            }
        }

        $token = Token_Manager::getToken();
        if (!$token) {
            return new WP_Error('no_token', 'Token OAuth manquant.');
        }
        $mailer = new PHPMailer(true);
    Plugin_SMTP_Mailer::configureMailer($mailer);
        try {
            $mailer->setFrom(get_option('admin_email'), get_bloginfo('name'));
            $mailer->addAddress($to);
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            foreach ($safe_headers as $header) {
                $mailer->addCustomHeader($header);
            }
            $mailer->send();
            return true;
        } catch (Exception $e) {
            // Log error but do not expose sensitive details
            error_log('Plugin SMTP Exemple send error: ' . $e->getMessage());
            return new WP_Error('send_failed', 'Envoi échoué.');
        }
    }
}