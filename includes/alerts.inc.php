<?php

/**
 * displays alerts section
 */

if (isset($_SESSION["errors"])) {
    echo '<div class="alert alert-danger" style="font-size: 0.8rem">
        <p>Please check your inputs for the following:</p>
        <ul class="text-start">';
    foreach ($_SESSION["errors"] as $message) {
        echo $message;
    }
    echo '</ul>
    </div>';
    unset($_SESSION["errors"]);
}

if (isset($_SESSION["success"])) {
    echo '<div class="alert alert-success" style="font-size: 0.8rem">
        <p class="mb-0">' . $_SESSION["success"] . '</p>
    </div>';
    unset($_SESSION["success"]);
}
