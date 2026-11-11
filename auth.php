<?php
// Start the session if it has not started.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check whether the user is logged in.
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
?>
