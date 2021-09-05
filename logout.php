<?php

require __DIR__ . '/middlewares/session.php';

// destroy current session
session_destroy();

// redirect to the login page
header('Location: /login.php');
