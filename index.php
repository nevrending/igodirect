<?php require __DIR__ . '/middlewares/session.php'; ?>
<?php require __DIR__ . '/middlewares/authenticated.php'; ?>
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
    </head>
    <body class="d-flex h-100 text-center text-white bg-dark">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">Hello <?php echo $_SESSION["user"]->name ?>!</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                        <a class="nav-link" href="logout.php">Logout</a>
                    </nav>
                </div>
            </header>

            <main class="px-3">
                <h1>Welcome back <?php echo $_SESSION["user"]->name ?>!</h1>
                <p class="lead">Your details</p>
                <p><?php // var_dump($_SESSION) ?></p>
                <ul class="text-start">
                    <li>User ID: <?php echo $_SESSION["user"]->id ?></li>
                    <li>Name: <?php echo $_SESSION["user"]->name ?></li>
                    <li>Email: <?php echo $_SESSION["user"]->email ?></li>
                    <li>Address: <?php echo $_SESSION["user"]->address ?></li>
                    <li>Attached File: <?php echo substr($_SESSION["user"]->file_path, 11) ?></li>
                    <li><embed src="<?php echo substr($_SESSION["user"]->file_path, 3) ?>" width="100%"></li>
                </ul>
                <!-- <p class="lead">
                    <a href="#" class="btn btn-lg btn-secondary fw-bold border-white bg-white">A button</a>
                </p> -->
            </main>

            <footer class="mt-auto text-white-50">
                <p>&copy; 2021 Yefta.com</p>
            </footer>
        </div>
    </body>
</html>
