<?php require __DIR__ . '/middlewares/session.php'; ?>
<?php require __DIR__ . '/middlewares/authenticated.php'; ?>
<?php require __DIR__ . '/middlewares/csrf.php'; ?>
<!doctype html>
<html lang="en" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="iGoDirect Group - Developer Task">
        <meta name="author" content="Yefta Sutanto">
        <title>Hello! | iGoDirect Group - Developer Task</title>

        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo-yeftacom.png">

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS for this page -->
        <link href="assets/css/index.css" rel="stylesheet">
        <style>
            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
              -webkit-appearance: none;
              margin: 0;
            }

            /* Firefox */
            input[type=number] {
              -moz-appearance: textfield;
            }
        </style>
    </head>
    <body class="d-flex h-100 text-center text-white bg-dark">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-5">
                <div>
                    <h3 class="float-md-start mb-0">Hello <?php echo $_SESSION["user"]->name ?>!</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                        <a class="nav-link" href="logout.php">Logout</a>
                    </nav>
                </div>
            </header>

            <main class="px-3 mb-3">
                <h1>Welcome back <?php echo $_SESSION["user"]->name ?>!</h1>
                <p class="lead">Your details</p>
                <ul class="text-start">
                    <li><strong>User ID:</strong> <?php echo $_SESSION["user"]->id ?></li>
                    <li><strong>Name:</strong> <?php echo $_SESSION["user"]->name ?></li>
                    <li><strong>Email:</strong> <?php echo $_SESSION["user"]->email ?></li>
                    <li><strong>Address:</strong> <?php echo $_SESSION["user"]->address ?></li>
                    <li><strong>Attached File:</strong> <?php echo substr($_SESSION["user"]->file_path, 11) ?></li>
                </ul>
                <embed src="<?php echo substr($_SESSION["user"]->file_path, 3) ?>" width="100%">
                <hr>
                <?php if ($_SESSION["user"]->two_factor_enabled) { ?>
                <p class="lead">Disable 2 Factor Authentication</p>
                <?php } else { ?>
                <p class="lead">Enable 2 Factor Authentication</p>
                <ol class="text-start">
                    <li>
                        Download Authenticator app from
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Play Store</a>
                        or
                        <a href="https://apps.apple.com/au/app/google-authenticator/id388497605" target="_blank">App Store</a>
                    </li>
                    <li>Scan the QR Code below and enter a resulting token to verify</li>
                </ol>
                <p class="lead">
                    <?php echo $_SESSION["2fa_qr_code"] ?>
                </p>
                <?php } ?>
                <form method="POST" action="controllers/twofactor.php" class="needs-validation" novalidate>
                    <input type="hidden" name="_token" value="<?php echo $easyCSRF->generate('_token') ?>">
                    <?php include __DIR__ . '/includes/alerts.inc.php' ?>
                    <div class="col-4 offset-4 mb-3">
                        <label for="token" class="form-label">Token</label>
                        <input type="hidden" name="op" value="toggle">
                        <input type="number" class="form-control" name="token" id="token" required>
                        <div class="invalid-feedback">
                            The token field is required.
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </main>

            <footer class="mt-auto text-white-50">
                <p>&copy; 2021 Yefta.com</p>
            </footer>
        </div>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/dashboard.js"></script>
        <script>
            session_expiry('<?php echo $_SESSION["expiry_time"]; ?>');
        </script>
    </body>
</html>
