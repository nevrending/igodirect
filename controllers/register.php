<?php

/**
 * handles Register and File Upload
 */

require dirname(__DIR__, 1) . '/config/db.php';
require dirname(__DIR__, 1) . '/middlewares/session.php';
require dirname(__DIR__, 1) . '/middlewares/csrf.php';
require dirname(__DIR__, 1) . '/helpers/functions.php';
require dirname(__DIR__, 1) . '/validators/UniqueRule.php';

use Rakit\Validation\Validator;
// use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use Ramsey\Uuid\Uuid;
use PragmaRX\Google2FAQRCode\Google2FA;


// this controller is only allowed to receive POST requests
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') !== 'POST') {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit('405 Not Allowed');
}

// check and prevent XSS/CSRF
// try {
//     $easyCSRF->check('_token', $_POST['_token']);
// } catch (InvalidCsrfTokenException $e) {
//     echo $e->getMessage();
//     exit;
// }

$validator = new Validator();
// setup unique validation rule using external custom validator class
$validator->addValidator('unique', new UniqueRule($pdo));

$validation = $validator->validate($_POST + $_FILES, [
    // validate inputs based on these rules
    'full_name'             => 'required',
    // checks if email address already exist in DB via custom validator
    'email'                 => 'required|email|unique:users,email',
    'password'              => 'required|min:8',
    'password_confirmation' => 'required|same:password',
    'address'               => 'required',
    'attach_something'      => 'required|uploaded_file:0,' . file_upload_max_size() . '|mimes:jpg,jpeg,png,pdf'
]);

if ($validation->fails()) {
    // validation fails, redirect back to Register Form with error messages
    $_SESSION["errors"] = $validation->errors()->all('<li>:message</li>');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

try {
    // generate UUID v4 for user id
    $id = Uuid::uuid4();
    // sanitize inputs
    $name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    // generate MySQL datetime
    $created_at = date("Y-m-d H:i:s", time());
    $updated_at = $created_at;

    // get details of the uploaded file
    $file_temp_path = $_FILES['attach_something']['tmp_name'];
    $file_name = filter_var($_FILES['attach_something']['name'], FILTER_SANITIZE_STRING);
    $new_file_name = time() . '-' . $file_name;
    // directory in which the uploaded file will be moved
    $uploads_dir = '../uploads/';
    $file_dest_path = $uploads_dir . $new_file_name;

    // store file to uploads dir
    if (!move_uploaded_file($file_temp_path, $file_dest_path)) {
        $_SESSION["errors"] = ["<li>Error storing uploaded file! Make sure the uploads directory is writable!</li>"];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // generate 2FA Secret Key
    $google2fa = new Google2FA();
    $two_factor_secret = $google2fa->generateSecretKey();

    // begin PDO tx
    $pdo->beginTransaction();

    // prepare insert statement, to insert registration data
    $statement = $pdo->prepare(
        "INSERT INTO users (id, name, email, password, address, file_path, two_factor_secret, created_at, updated_at)
        VALUES (:id, :name, :email, :password, :address, :file_path, :two_factor_secret, :created_at, :updated_at)"
    );
    $statement->bindParam(':id', $id, PDO::PARAM_STR);
    $statement->bindParam(':name', $name, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', $password, PDO::PARAM_STR);
    $statement->bindParam(':address', $address, PDO::PARAM_STR);
    $statement->bindParam(':file_path', $file_dest_path, PDO::PARAM_STR);
    $statement->bindParam(':two_factor_secret', $two_factor_secret, PDO::PARAM_STR);
    $statement->bindParam(':created_at', $created_at, PDO::PARAM_STR);
    $statement->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
    $statement->execute();

    // commit tx to DB
    $pdo->commit();

    $_SESSION["success"] = "Thanks for Registering!<br>You can now login.";
    header('Location: /login.php');
    exit;
} catch (Exception $e) {
    // rollback tx on error
    $pdo->rollBack();

    // on exception, redirect back to Register Form with error message
    $_SESSION["errors"] = [
        "<li>Sorry, there is something wrong on our end! Please try again later!</li>",
        $e->getMessage()
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
