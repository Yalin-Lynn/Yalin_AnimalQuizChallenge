<?php
session_start();

// Remove all session variables.
$_SESSION = [];

// Delete the session cookie if one exists.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), "", [
        "expires" => time() - 3600,
        "path" => $params["path"],
        "domain" => $params["domain"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"]
    ]);
}

// Destroy the session.
session_destroy();

// Redirect to login page.
header("Location: login.php");
exit;
?>
