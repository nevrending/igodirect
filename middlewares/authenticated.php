<?php

/**
 * checks if user is authenticated. If not, redirect to Login page
 */

if (!$_SESSION["authenticated"]) {
    header('Location: /login.php');
    exit;
}
