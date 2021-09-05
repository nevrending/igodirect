<?php

/**
 * displays alerts section
 */

if (isset($_SESSION["errors"])) {
    echo '<div class="alert alert-danger">
        <p>Please check your inputs for the following:</p>
        <ul>';
    foreach ($_SESSION["errors"] as $message) {
        echo $message;
    }
    echo '</ul>
    </div>';
    unset($_SESSION["errors"]);
}

if (isset($_SESSION["success"])) {
    echo '<div class="alert alert-success">
        <p class="mb-0">' . $_SESSION["success"] . '</p>
    </div>';
    unset($_SESSION["success"]);
}
