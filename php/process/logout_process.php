<?php
// Destroy the session cookie.
if (ini_set('session.use_cookies', 1) === TRUE) {
    
}

session_start();

// Unset all of the session variables.
$_SESSION = array();

$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

// Destroy the session session data.
session_destroy();

// Redirect to the login page (or desired landing page after logout).
header('Location: ../../index.php');
exit;

