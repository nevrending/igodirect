<?php

/**
 * refresh session lifetime
 */

require dirname(__DIR__, 1) . '/middlewares/session.php';
require dirname(__DIR__, 1) . '/middlewares/authenticated.php';

session_regenerate_id(true);

$_SESSION["expiry_time"] = time() + THIRTY_MINUTES_IN_SECONDS;

// redirect to home page
header('Location: /');
