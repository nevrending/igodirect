<?php require __DIR__ . '/middlewares/dotenv.php'; ?>
<?php require __DIR__ . '/middlewares/session.php'; ?>
<?php require __DIR__ . '/middlewares/csrf.php'; ?>
<?php require __DIR__ . '/helpers/functions.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="iGoDirect Group - Developer Task">
        <meta name="author" content="Yefta Sutanto">
        <title>Register | iGoDirect Group - Developer Task</title>

        <!-- Favicon -->
        <link rel="icon" href="assets/img/logo-yeftacom.png">

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS for this page -->
        <link href="assets/css/register.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container">
            <main>
                <div class="py-3 text-center">
                    <img class="d-block mx-auto mb-4" src="assets/img/logo-yeftacom.png" alt="" width="150" height="150">
                    <h2>Registration Form</h2>
                    <p class="lead">All fields are required.</p>
                    <p><a href="login.php">Already have an account? Login now! &rarr;</a></p>
                </div>

                <div class="row g-5">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <?php include __DIR__ . '/includes/alerts.inc.php' ?>
                        <form method="POST" action="controllers/register.php" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <input type="hidden" name="_token" value="<?php echo $easyCSRF->generate('_token') ?>">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" required>
                                    <div class="invalid-feedback">
                                        The full name field is required.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                    <div class="invalid-feedback">
                                        The password field is required.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    <div class="invalid-feedback">
                                        The password confirmation does not match.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" id="address" placeholder="1234 Main St" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid address.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <?php const ONE_MB_IN_BYTES = 1048576; ?>
                                    <label for="attach_something" class="form-label">Attach Something <small>(Max size: <?php echo bcdiv(file_upload_max_size(), ONE_MB_IN_BYTES) ?> MB)</small></label>
                                    <input type="file" class="form-control" name="attach_something" id="attach_something" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div class="invalid-feedback">
                                        Please attach a JPEG, PNG, or PDF file.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <button class="w-100 btn btn-primary btn-lg" type="submit">Register</button>
                        </form>
                    </div>
                </div>
            </main>

            <footer class="my-5 text-muted text-center text-small">
                <p class="mb-1">&copy; 2021 Yefta.com</p>
            </footer>
        </div>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_ENV['MAPS_API_KEY'] ?>&libraries=places"></script>
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/register.js"></script>
    </body>
</html>
