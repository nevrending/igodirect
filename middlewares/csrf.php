<?php

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

/**
 * initialize CSRF provider
 */

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);
