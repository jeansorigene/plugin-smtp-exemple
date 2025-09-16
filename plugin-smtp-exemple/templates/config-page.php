<?php
$auth_url = Plugin_SMTP_OAuthHandler::buildAuthUrl();
?>
<div class="wrap">
    <h2>Plugin SMTP Exemple OAuth</h2>
    <?php if ($token): ?>
        <p><strong>Token OAuth stocké.</strong></p>
        <form method="post" action="">
            <input type="hidden" name="plugin_action" value="delete_token" />
            <button type="submit" class="button">Supprimer le token</button>
        </form>
        <hr>
        <h3>Envoyer un email de test</h3>
        <form method="post" action="">
            <input type="hidden" name="plugin_action" value="send_test_email" />
            <label for="test_email">Adresse email de destination :</label>
            <input type="email" name="test_email" id="test_email" required placeholder="votre@email.com" />
            <button type="submit" class="button button-primary">Envoyer l'email de test</button>
        </form>
    <?php else: ?>
        <a href="<?= esc_url($auth_url) ?>" class="button button-primary">Autoriser via OAuth</a>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="notice notice-success"><p>Token OAuth récupéré avec succès !</p></div>
    <?php endif; ?>
</div>