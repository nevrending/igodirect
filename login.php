<?php require __DIR__ . '/middlewares/session.php'; ?>
<?php require __DIR__ . '/middlewares/csrf.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="iGoDirect Group - Developer Task">
        <meta name="author" content="Yefta Sutanto">
        <title>Login | iGoDirect Group - Developer Task</title>

        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo-yeftacom.png">

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS for this page -->
        <link href="assets/css/login.css" rel="stylesheet">
    </head>
    <body class="text-center">
        <main class="form-signin">
            <form method="POST" action="controllers/login.php" class="needs-validation" novalidate>
                <input type="hidden" name="_token" value="<?php echo $easyCSRF->generate('_token') ?>">
                <img class="mb-4" src="assets/img/logo-yeftacom.png" alt="" width="150" height="150">
                <h1 class="h3 mb-3 fw-normal">Login</h1>
                <?php include __DIR__ . '/includes/alerts.inc.php' ?>
                <div class="form-floating">
                    <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com" required>
                    <label for="email">Email</label>
                    <div class="invalid-feedback mb-3">
                        The email field is required.
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="examplepassword" required>
                    <label for="password">Password</label>
                    <div class="invalid-feedback mb-3">
                        The password field is required.
                    </div>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
                <p class="mt-3"><a href="register.php">Don't have an account? Register now! &rarr;</a></p>
                <p class="mt-5 mb-3 text-muted">&copy; 2021 Yefta.com</p>
            </form>
        </main>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/form-validation.js"></script>
    </body>
</html>
