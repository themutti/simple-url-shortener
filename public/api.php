<?php
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . "/../app/Controllers/UrlController.php";

use App\Controllers\UrlController;

$controller = new UrlController($_GET);
$controller->redirectToLongUrl();
