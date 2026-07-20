<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../api/config/env.php';

require_once __DIR__ . '/../api/models/get.model.php';
require_once __DIR__ . '/../api/models/connection.php';
require_once __DIR__ . '/../api/models/SecuencialModel.php';

require_once __DIR__ . '/../cms/controllers/secret.controller.php';
require_once __DIR__ . '/../cms/controllers/curl.controller.php';
require_once __DIR__ . '/../cms/controllers/apiclient.controller.php';
require_once __DIR__ . '/../cms/controllers/xml.controller.php';
require_once __DIR__ . '/../cms/controllers/sri.controller.php';
require_once __DIR__ . '/../cms/controllers/csrf.controller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
