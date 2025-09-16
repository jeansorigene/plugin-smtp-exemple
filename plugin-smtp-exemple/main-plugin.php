<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
Plugin Name: Main SMTP Module
Description: Generic WordPress plugin for SMTP email sending with OAuth support.
Version: 1.0.0
Date: 2025-09-09
Author: Anonymous
*/

// Security: Prevent direct file access
if (!defined('ABSPATH')) exit;

// Autoload dependencies via Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Include plugin modules
require_once __DIR__ . '/private/token-manager.php'; // OAuth token management
require_once __DIR__ . '/includes/smtp-mailer.php'; // Mailer configuration
require_once __DIR__ . '/includes/send-smtp.php';   // Email sending
require_once __DIR__ . '/includes/start-smtp.php';  // SMTP initialization
require_once __DIR__ . '/admin/admin-page.php';     // Admin page
require_once __DIR__ . '/admin/oauth-handler.php';  // OAuth handler
// ...add other includes as needed...
