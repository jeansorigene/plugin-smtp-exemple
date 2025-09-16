<!-- FRANÇAIS -->
# 🚀 Nouvelle Release : v1.0.0

Cette version marque une étape majeure dans le développement du plugin modulaire grâce à l'intégration de plusieurs fonctionnalités de sécurité et de connectivité :

## ✅ Fonctionnalités principales

- **Intégration OAuth** : Authentification via des fournisseurs OAuth (Google, Microsoft, etc.).
- **SMTP/Microsoft Entra ID** : Envoi d'emails sécurisé via SMTP classique ou Microsoft Entra ID.
- **reCaptcha** : Protection avancée contre les bots et les attaques automatisées.

## 📝 Notes de mise à jour

- Correction de bugs mineurs.
- Amélioration de la documentation.
- Tests unitaires ajoutés pour les nouveaux modules.

## 📦 Installation & Mise à jour

1. Téléchargez l'archive (.zip ou .tar.gz) ci-dessous.
2. Suivez le guide de configuration dans le README du dépôt.
3. Configurez vos clés OAuth, SMTP/Entra ID, et reCaptcha dans le fichier de configuration.

## 📚 Documentation

Pour plus d'informations sur la configuration des nouveaux modules, consultez la section dédiée dans le [README](./README.md).

## Remerciements

Merci à tous les contributeurs et testeurs pour leur soutien et leurs retours précieux !

---

## [PLUGIN-SMTP-EXEMPLE]

**Intégration OAuth2 opérationnelle et testée.**

Ce plugin WordPress intègre OAuth2 pour l'envoi SMTP, avec gestion sécurisée du token dans un dossier privé hors du dossier public. L'envoi d'email via le formulaire d'admin fonctionne (testé avec domaine exemple.ca).

### Fonctionnalités principales

- Envoi d'emails via SMTP avec authentification OAuth2 (Gmail, Outlook, serveurs personnalisés).
- Stockage et gestion du token OAuth dans un dossier sécurisé hors webroot.
- Formulaire d'envoi de mail de test intégré à l'admin WordPress.
- Compatible WordPress 6.x, Twenty Twenty-Five, PHP 8+.
- Respect des normes de sécurité et des bonnes pratiques DevOps.

### Structure modulaire et sécurité

```text
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

Le fichier `token-manager.php` et le fichier `token.json` sont placés dans un dossier privé, par exemple : `/var/www/private/`.

### Installation et utilisation

1. Place tous les fichiers du plugin dans le dossier `wp-content/plugins/plugin-smtp-exemple` de ton site WordPress.
2. Place `token-manager.php` et `token.json` dans un dossier privé sur ton serveur (ex : `/var/www/private/`).
3. Défini les constantes OAuth dans le fichier `wp-config.php` :

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

4. Active le plugin dans l'admin WordPress.
5. Autorise le plugin via OAuth depuis la page d'admin du plugin.
6. Envoie un email de test depuis la page admin pour valider l'intégration OAuth2 et SMTP.

### Sécurité et gestion des tokens

- **Gestion du token OAuth :** Le token d'accès est stocké dans un fichier sécurisé (`token.json`) hors du webroot.
- La classe `token-manager.php` gère la lecture, l'écriture et la suppression du token côté serveur.
- Pour renforcer la sécurité, ajoutez ce bloc dans le fichier `.htaccess` du dossier contenant le token :

    ```apache
    <Files "token.json">
        Order allow,deny
        Deny from all
    </Files>
    ```

    Cela bloque tout accès HTTP direct au fichier `token.json`.
- Le dossier privé doit être placé **hors du webroot** pour empêcher tout accès public.
- Les permissions du fichier sont restreintes à l'utilisateur serveur (`chmod 0600`).

### Exemple de contenu du token.json

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

> **Astuce sécurité :**
> Pour vérifier la validité du fichier `token.json`, utilisez le validateur JSON en ligne [jsonlint.com](https://jsonlint.com/) : [https://jsonlint.com/](https://jsonlint.com/) ou un script PHP :
>
> ```php
> $json = file_get_contents('/chemin/vers/token.json');
> $data = json_decode($json, true);
> if (json_last_error() === JSON_ERROR_NONE) {
>     echo 'Le format JSON est valide.';
> } else {
>     echo 'Erreur de format JSON : ' . json_last_error_msg();
> }
> ```
>
> Cela permet d'éviter toute erreur de syntaxe et de garantir la sécurité du jeton.

### Résilience et fallback SMTP

Si l'authentification OAuth2 échoue (token expiré, erreur d'API, etc.), le plugin peut automatiquement basculer sur l'envoi SMTP classique grâce aux constantes définies dans `wp-config.php` :

```php
define('SMTP_USER', 'smtp@example.com');
define('SMTP_PASS', 'votre-mot-de-passe-ou-app-password');
define('SMTP_FROM', 'smtp@example.com');
```

Cela garantit la continuité de service pour l'envoi d'emails, même en cas de problème OAuth2.

### Bonnes pratiques

- Ne jamais exposer `token.json` ou le dossier privé dans le webroot.
- Garder à jour les dépendances et la configuration OAuth.
- Utiliser un serveur sécurisé avec accès restreint.

### Auteur, version et date

- **Auteur :** Jeans smail Origène
- **Version :** 1.0.0 (stable, OAuth2 opérationnel)
- **Date :** 2025-09-10

### Licence

MIT

### Contact

Pour toute question ou suggestion, contacter l'auteur via GitHub.

---

<!-- ENGLISH -->
# 🚀 New Release: v1.0.0

This version marks a major milestone in the development of the modular plugin with the integration of several security and connectivity features:

## ✅ Main Features

- **OAuth Integration**: Authentication via OAuth providers (Google, Microsoft, etc.).
- **SMTP/Microsoft Entra ID**: Secure email sending via classic SMTP or Microsoft Entra ID.
- **reCaptcha**: Advanced protection against bots and automated attacks.

## 📝 Release Notes

- Minor bug fixes.
- Improved documentation.
- Unit tests added for new modules.

## 📦 Installation & Update

1. Download the archive (.zip or .tar.gz) below.
2. Follow the setup guide in the repository README.
3. Configure your OAuth, SMTP/Entra ID, and reCaptcha keys in the configuration file.

## 📚 Documentation

For more information on configuring the new modules, see the dedicated section in the [README](./README.md).

## 🔧 Acknowledgments

Thanks to all contributors and testers for their valuable support and feedback!

---

## [PLUGIN-SMTP-EXEMPLE]

**Operational and tested OAuth2 integration.**

This WordPress plugin integrates OAuth2 for SMTP sending, with secure token management in a private folder outside the public directory. Email sending via the admin form works (tested with example.ca domain).

### Main Features

- Send emails via SMTP with OAuth2 authentication (Gmail, Outlook, custom servers).
- Store and manage the OAuth token in a secure folder outside the webroot.
- Integrated test mail sending form in WordPress admin.
- Compatible with WordPress 6.x, Twenty Twenty-Five, PHP 8+.
- Complies with security standards and DevOps best practices.

### Modular Structure and Security

```text
plugin-smtp-exemple/
├── composer.json
├── vendor/
├── README.md
├── includes/
│   ├── smtp-mailer.php      # Mailer configuration (secure, uses wp-config.php constants)
│   ├── send-smtp.php        # Email sending (input sanitization and validation)
│   ├── start-smtp.php       # SMTP initialization (rights and token check, logging)
├── admin/
│   ├── admin-page.php       # Plugin admin page
│   ├── oauth-handler.php    # OAuth2 handler (uses wp-config.php constants)
├── templates/
│   └── config-page.php      # Config interface and OAuth button
├── assets/
│   └── css/js (if needed)
└── plugin-smtp-exemple.php       # Main file, security, autoload, modular includes
```

### Security and Best Practices

- Block direct access to all PHP files (`if (!defined('ABSPATH')) exit;`)
- Automatic dependency loading via Composer
- Input validation and sanitization for email sending
- User rights check and logging in initialization
- Use constants defined in `wp-config.php` for OAuth2 and SMTP
- Internationalization of error messages

### Optimization

- Modular structure for easier maintenance
- Explicit comments in each file
- Centralized error and configuration management

The `token-manager.php` and `token.json` files are placed in a private folder, e.g.: `/var/www/private/`.

### Installation and Usage

1. Place all plugin files in the `wp-content/plugins/plugin-smtp-exemple` folder of your WordPress site.
2. Place `token-manager.php` and `token.json` in a private folder on your server (e.g.: `/var/www/private/`).
3. Define OAuth constants in the `wp-config.php` file:

    ```php
    define('OAUTH_CLIENT_ID', 'your-client-id');
    define('OAUTH_CLIENT_SECRET', 'your-client-secret');
    define('OAUTH_AUTH_URL', 'https://provider.com/auth');
    define('OAUTH_TOKEN_URL', 'https://provider.com/token');
    define('OAUTH_REDIRECT_URI', 'https://your-site.com/wp-admin/admin-post.php?action=oauth_callback');
    define('SMTP_HOST', 'smtp.provider.com'); // Optional
    define('SMTP_PORT', 587); // Optional
    define('SMTP_SECURE', 'tls'); // Optional
    ```

4. Activate the plugin in WordPress admin.
5. Authorize the plugin via OAuth from the plugin admin page.
6. Send a test email from the admin page to validate OAuth2 and SMTP integration.

### Security and Token Management

- **OAuth token management:** The access token is stored in a secure file (`token.json`) outside the webroot.
- The `token-manager.php` class handles reading, writing, and deleting the token server-side.
- To enhance security, add this block to the `.htaccess` file in the token folder:

    ```apache
    <Files "token.json">
        Order allow,deny
        Deny from all
    </Files>
    ```

    This blocks all direct HTTP access to the `token.json` file.
- The private folder must be placed **outside the webroot** to prevent public access.
- File permissions are restricted to the server user (`chmod 0600`).

### Example token.json content

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

> **Security tip:**
> To check the validity of the `token.json` file, use the online JSON validator [jsonlint.com](https://jsonlint.com/): [https://jsonlint.com/](https://jsonlint.com/) or a PHP script:
>
> ```php
> $json = file_get_contents('/path/to/token.json');
> $data = json_decode($json, true);
> if (json_last_error() === JSON_ERROR_NONE) {
>     echo 'JSON format is valid.';
> } else {
>     echo 'JSON format error: ' . json_last_error_msg();
> }
> ```
>
> This helps avoid syntax errors and ensures token security.

### Resilience and SMTP Fallback

If OAuth2 authentication fails (expired token, API error, etc.), the plugin can automatically fall back to classic SMTP using constants defined in `wp-config.php`:

```php
define('SMTP_USER', 'smtp@example.com');
define('SMTP_PASS', 'your-password-or-app-password');
define('SMTP_FROM', 'smtp@example.com');
```

This ensures email sending continuity even if OAuth2 fails.

### Best Practices

- Never expose `token.json` or the private folder in the webroot.
- Keep dependencies and OAuth configuration up to date.
- Use a secure server with restricted access.

### Author, Version, and Date

- **Author:** Jeans smail Origène
- **Version:** 1.0.0 (stable, operational OAuth2)
- **Date:** 2025-09-10

### License

MIT

### Contact

For questions or suggestions, contact the author via GitHub.
