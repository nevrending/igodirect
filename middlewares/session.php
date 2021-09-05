<?php

/**
 * begin new session if there aren't any
 */

const THIRTY_MINUTES_IN_SECONDS = 1800;

if (!isset($_SESSION)) {
    ini_set('session.gc_maxlifetime', THIRTY_MINUTES_IN_SECONDS);
    session_set_cookie_params(THIRTY_MINUTES_IN_SECONDS);
    session_start();
}
