<?php

/**
 * handles DB connection
 */

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
require dirname(__DIR__, 1) . '/middlewares/dotenv.php';


// setup DB connection info
$DB_HOST = $_ENV['DB_HOST'];
$DB_PORT = $_ENV['DB_PORT'];
$DB_DATABASE = $_ENV['DB_DATABASE'];
$DB_USERNAME = $_ENV['DB_USERNAME'];
$DB_PASSWORD = $_ENV['DB_PASSWORD'];

try {
    // set dsn for PDO
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $DB_HOST, $DB_PORT, $DB_DATABASE);
    // initialize new PDO object
    $pdo = new PDO($dsn, $DB_USERNAME, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // on PDO exception
    echo $e->getMessage();
    die();
}
