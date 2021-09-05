<?php

/**
 * begin new session if there aren't any
 */

if (!isset($_SESSION)) {
    session_start();
}
