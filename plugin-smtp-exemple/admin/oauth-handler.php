<?php
class Plugin_SMTP_OAuthHandler {
    public static function handleOAuthCallback() {
        if (!current_user_can('manage_options')) {
            wp_die('Permission refusée.');
        }
        if (isset($_GET['code'])) {
            $code = sanitize_text_field($_GET['code']);
            $token = self::exchangeCodeForToken($code);
            if ($token && isset($token['access_token'])) {
                Token_Manager::saveToken($token);
                    wp_redirect(admin_url('options-general.php?page=plugin-smtp-oauth&success=1'));
                exit;
            } else {
                wp_die('Erreur lors de la récupération du token OAuth.');
            }
        }
    }

    public static function buildAuthUrl() {
        $params = [
            'client_id'    => defined('OAUTH_CLIENT_ID') ? OAUTH_CLIENT_ID : '',
            'redirect_uri' => defined('OAUTH_REDIRECT_URI') ? OAUTH_REDIRECT_URI : '',
            'response_type'=> 'code',
            'scope'        => 'email smtp',
            'state'        => wp_create_nonce('plugin_oauth_state'),
        ];
        $authUrl = defined('OAUTH_AUTH_URL') ? OAUTH_AUTH_URL : '';
        return $authUrl . '?' . http_build_query($params);
    }

    private static function exchangeCodeForToken($code) {
        $body = [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => defined('OAUTH_REDIRECT_URI') ? OAUTH_REDIRECT_URI : '',
            'client_id'     => defined('OAUTH_CLIENT_ID') ? OAUTH_CLIENT_ID : '',
            'client_secret' => defined('OAUTH_CLIENT_SECRET') ? OAUTH_CLIENT_SECRET : '',
        ];
        $tokenUrl = defined('OAUTH_TOKEN_URL') ? OAUTH_TOKEN_URL : '';
        $response = wp_remote_post($tokenUrl, [
            'body'    => $body,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        if (is_wp_error($response)) {
            return null;
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}