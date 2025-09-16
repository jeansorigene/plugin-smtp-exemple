# Plugin SMTP Exemple

**Intégration OAuth2 opérationnelle et testée.**

Ce plugin WordPress intègre OAuth2 pour l’envoi SMTP, avec gestion sécurisée du token dans un dossier privé hors du dossier public. L’envoi d’email via le formulaire d’admin fonctionne (testé avec domaine exemple.ca).

## Fonctionnalités principales

- Envoi d’emails via SMTP avec authentification OAuth2 (Gmail, Outlook, serveurs personnalisés).
- Stockage et gestion du token OAuth dans un dossier sécurisé hors webroot.
- Formulaire d’envoi de mail de test intégré à l’admin WordPress.
- Compatible WordPress 6.x, Twenty Twenty-Five, PHP 8+.
- Respect des normes de sécurité et des bonnes pratiques DevOps.

## Structure modulaire et sécurité

```
plugin-smtp-exemple/
├── composer.json
├── vendor/
├── README.md
├── includes/
│   ├── smtp-mailer.php      # Configuration du mailer (sécurisé, utilise les constantes wp-config.php)
│   ├── send-smtp.php        # Envoi des emails (sanitization et validation des entrées)
│   ├── start-smtp.php       # Initialisation SMTP (vérification des droits et du token, journalisation)
├── admin/
│   ├── admin-page.php       # Page admin du plugin
│   ├── oauth-handler.php    # Handler OAuth2 (utilise les constantes wp-config.php)
├── templates/
│   └── config-page.php      # Interface de configuration et bouton OAuth
├── assets/
│   └── css/js (si nécessaire)
└── plugin-smtp-exemple.php       # Fichier principal, sécurité, autoload, includes modulaires
```

### Sécurité et bonnes pratiques
- Blocage de l'accès direct à tous les fichiers PHP (`if (!defined('ABSPATH')) exit;`)
- Chargement automatique des dépendances via Composer
- Validation et sanitation des entrées dans l'envoi d'email
- Vérification des droits utilisateur et journalisation dans l'initialisation
- Utilisation des constantes définies dans `wp-config.php` pour OAuth2 et SMTP
- Internationalisation des messages d'erreur

### Optimisation
- Structure modulaire pour faciliter la maintenance
- Commentaires explicites dans chaque fichier
- Gestion centralisée des erreurs et de la configuration

Le fichier `token-manager.php` et le fichier `token.json` sont placés dans un dossier privé, par exemple : `/var/www/private/`.

## Installation et utilisation

1. Place tous les fichiers du plugin dans le dossier `wp-content/plugins/plugin-smtp-exemple` de ton site WordPress.
2. Place `token-manager.php` et `token.json` dans un dossier privé sur ton serveur (ex : `/var/www/private/`).
3. Définis les constantes OAuth dans le fichier `wp-config.php` :

    ```php
    define('OAUTH_CLIENT_ID', 'votre-client-id');
    define('OAUTH_CLIENT_SECRET', 'votre-client-secret');
    define('OAUTH_AUTH_URL', 'https://provider.com/auth');
    define('OAUTH_TOKEN_URL', 'https://provider.com/token');
    define('OAUTH_REDIRECT_URI', 'https://votre-site.com/wp-admin/admin-post.php?action=oauth_callback');
    define('SMTP_HOST', 'smtp.provider.com'); // Optionnel
    define('SMTP_PORT', 587); // Optionnel
    define('SMTP_SECURE', 'tls'); // Optionnel
    ```

4. Active le plugin dans l’admin WordPress.
5. Autorise le plugin via OAuth depuis la page d’admin du plugin.
6. Envoie un email de test depuis la page admin pour valider l’intégration OAuth2 et SMTP.

## Sécurité et gestion des tokens

- **Gestion du token OAuth :** Le token d’accès est stocké dans un fichier sécurisé (`token.json`) hors du webroot.
- La classe `token-manager.php` gère la lecture, l’écriture et la suppression du token côté serveur.
- Pour renforcer la sécurité, ajoutez ce bloc dans le fichier `.htaccess` du dossier contenant le token :
    ```apache
    <Files "token.json">
        Order allow,deny
        Deny from all
    </Files>
    ```
    Cela bloque tout accès HTTP direct au fichier `token.json`.
- Le dossier privé doit être placé **hors du webroot** pour empêcher tout accès public.
- Les permissions du fichier sont restreintes à l’utilisateur serveur (`chmod 0600`).

## Exemple de contenu du token.json

```json
{
  "access_token": "ya29.a0AfH6EXEMPLEDETOKEN",
  "refresh_token": "1//0gEXEMPLEREFRESHTOKEN",
  "expires_in": 3599,
  "scope": "email smtp",
  "token_type": "Bearer",
  "created": 1694112000
}
```

> **Astuce sécurité :**
> Pour vérifier la validité du fichier `token.json`, utilisez le validateur JSON en ligne [jsonlint.com](https://jsonlint.com/) : [https://jsonlint.com/](https://jsonlint.com/) ou un script PHP :
> ```php
> $json = file_get_contents('/chemin/vers/token.json');
> $data = json_decode($json, true);
> if (json_last_error() === JSON_ERROR_NONE) {
>     echo 'Le format JSON est valide.';
> } else {
>     echo 'Erreur de format JSON : ' . json_last_error_msg();
> }
> ```
> Cela permet d’éviter toute erreur de syntaxe et de garantir la sécurité du jeton.

## Résilience et fallback SMTP

Si l’authentification OAuth2 échoue (token expiré, erreur d’API, etc.), le plugin peut automatiquement basculer sur l’envoi SMTP classique grâce aux constantes définies dans `wp-config.php` :

```php
define('SMTP_USER', 'smtp@example.com');
define('SMTP_PASS', 'votre-mot-de-passe-ou-app-password');
define('SMTP_FROM', 'smtp@example.com');
```

Cela garantit la continuité de service pour l’envoi d’emails, même en cas de problème OAuth2.

## Bonnes pratiques

- Ne jamais exposer `token.json` ou le dossier privé dans le webroot.
- Garder à jour les dépendances et la configuration OAuth.
- Utiliser un serveur sécurisé avec accès restreint.

## Auteur, version et date

- **Auteur :** Jeans smail Origène
- **Version :** 1.0.0 (stable, OAuth2 opérationnel)
- **Date :** 2025-09-10

## Licence

MIT

## Contact

Pour toute question ou suggestion, contacter l’auteur via GitHub.
