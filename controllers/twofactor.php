<?php

/**
 * handles 2 Factor Auth verification after Login
 */

require dirname(__DIR__, 1) . '/config/db.php';
require dirname(__DIR__, 1) . '/middlewares/session.php';
require dirname(__DIR__, 1) . '/middlewares/csrf.php';

use Rakit\Validation\Validator;
use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use PragmaRX\Google2FAQRCode\Google2FA;


// this controller is only allowed to receive POST requests
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') !== 'POST') {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit('405 Not Allowed');
}

// check and prevent XSS/CSRF
try {
    $easyCSRF->check('_token', $_POST['_token']);
} catch (InvalidCsrfTokenException $e) {
    echo $e->getMessage();
    exit;
}

$validator = new Validator();

$validation = $validator->validate($_POST + $_FILES, [
    'op'                    => 'alpha',
    'token'                 => 'required|numeric'
]);

if ($validation->fails()) {
    // validation fails, redirect back to Login Form with error messages
    $_SESSION["errors"] = $validation->errors()->all('<li>:message</li>');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

try {
    // prepare select statement, to find current user
    $statement = $pdo->prepare(
        "SELECT * FROM users WHERE email = :email"
    );
    $statement->bindParam(':email', $_SESSION["user"]->email, PDO::PARAM_STR);
    $statement->execute();

    // fetch resulting row
    $user = $statement->fetch(PDO::FETCH_OBJ);

    // sanitize inputs
    $op = filter_var($_POST['op'], FILTER_SANITIZE_STRING);
    $token = filter_var($_POST['token'], FILTER_SANITIZE_NUMBER_INT);

    // verify token
    $google2fa = new Google2FA();
    $valid = $google2fa->verifyKey($user->two_factor_secret, $token);

    if ($valid) {
        // token is valid
        if ($op === "toggle") {
            // if op is to toggle 2FA
            if ($user->two_factor_enabled) {
                $two_factor_toggle = 0;
            } else {
                $two_factor_toggle = 1;
            }

            try {
                // begin PDO tx
                $pdo->beginTransaction();

                // prepare update statement, indicate 2FA is enabled
                $statement = $pdo->prepare(
                    "UPDATE users SET two_factor_enabled = :two_factor_enabled WHERE users.id = :id"
                );
                $statement->bindParam(':id', $user->id, PDO::PARAM_STR);
                $statement->bindParam(':two_factor_enabled', $two_factor_toggle, PDO::PARAM_INT);
                $statement->execute();

                // commit tx to DB
                $pdo->commit();
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

            $_SESSION["user"]->two_factor_enabled = $two_factor_toggle;
            if ($two_factor_toggle) {
                $_SESSION["success"] = "2FA Enabled!";
            } else {
                $_SESSION["success"] = "2FA Disabed!";
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            // op is to validate 2FA on Login
            $_SESSION["authenticated"] = true;
            $_SESSION["login_time"] = time();
            $_SESSION["expiry_time"] = $_SESSION["login_time"] + THIRTY_MINUTES_IN_SECONDS;

            // redirect to home page
            header('Location: /');
            exit;
        }
    } else {
        // token is invalid
        $_SESSION["errors"] = ["<li>Invalid 2FA token! Please try again.</li>"];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} catch (Exception $e) {
    // on exception, redirect back to Register Form with error message
    $_SESSION["errors"] = [
        "<li>Sorry, there is something wrong on our end! Please try again later!</li>",
        $e->getMessage()
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
