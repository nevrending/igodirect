<?php

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

/**
 * load .env file into env vars
 */

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->safeLoad();
