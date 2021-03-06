<?php

/**
 * handles Login
 */

require dirname(__DIR__, 1) . '/config/db.php';
require dirname(__DIR__, 1) . '/middlewares/session.php';
require dirname(__DIR__, 1) . '/middlewares/csrf.php';

use Rakit\Validation\Validator;
// use EasyCSRF\Exceptions\InvalidCsrfTokenException;
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

$validation = $validator->validate($_POST + $_FILES, [
    'email'                 => 'required|email',
    'password'              => 'required|min:8'
]);

if ($validation->fails()) {
    // validation fails, redirect back to Login Form with error messages
    $_SESSION["errors"] = $validation->errors()->all('<li>:message</li>');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

try {
    // sanitize inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // prepare select statement, to find whether user exists
    $statement = $pdo->prepare(
        "SELECT * FROM users WHERE email = :email"
    );
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    // fetch resulting row
    $user = $statement->fetch(PDO::FETCH_OBJ);

    // check if user exists
    if (empty($user)) {
        // if not exist, redirect to login page
        $_SESSION["errors"] = ["<li>Email not found!</li>"];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (password_verify($password, $user->password)) {
        // generate new session and invalidate previous
        session_regenerate_id(true);

        $google2fa = new Google2FA();
        $qr_code = $google2fa->getQRCodeInline(
            "Yefta.com",
            $user->email,
            $user->two_factor_secret
        );

        // remove sensitive data
        unset($user->password);
        unset($user->two_factor_secret);

        $_SESSION["user"] = $user;
        $_SESSION["2fa_qr_code"] = $qr_code;

        if ($user->two_factor_enabled) {
            // redirect to home page
            header('Location: /twofactor.php');
            exit;
        } else {
            $_SESSION["authenticated"] = true;
            $_SESSION["login_time"] = time();
            $_SESSION["expiry_time"] = $_SESSION["login_time"] + THIRTY_MINUTES_IN_SECONDS;

            // redirect to home page
            header('Location: /');
            exit;
        }
    }

    // if we reach here, means password is wrong, redirect to login with error message
    $_SESSION["errors"] = ["<li>Wrong password!</li>"];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} catch (Exception $e) {
    // on exception, redirect back to Register Form with error message
    $_SESSION["errors"] = [
        "<li>Sorry, there is something wrong on our end! Please try again later!</li>",
        $e->getMessage()
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
