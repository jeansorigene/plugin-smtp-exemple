<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
Plugin Name: Plugin SMTP Exemple
Description: Plugin WordPress pour l’envoi SMTP avec OAuth.
Version: 1.0.0
Date: 2025-09-09
Author: Jean Smail Origène
*/

// Sécurité : empêche l'accès direct au fichier
if (!defined('ABSPATH')) exit;

// Chargement automatique des dépendances via Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Inclusion des modules du plugin
require_once '/home/variable/private/token-manager.php'; // Gestion des tokens OAuth
require_once __DIR__ . '/includes/smtp-mailer.php'; // Configuration du mailer
require_once __DIR__ . '/includes/send-smtp.php';   // Envoi des emails
require_once __DIR__ . '/includes/start-smtp.php';  // Initialisation SMTP
require_once __DIR__ . '/admin/admin-page.php';     // Page admin
require_once __DIR__ . '/admin/oauth-handler.php';  // Handler OAuth
// ...ajoute d'autres includes si besoin...
