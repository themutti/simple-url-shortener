<?php
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\UrlController;

switch ($_SERVER["REQUEST_METHOD"]) {
  case "GET": {
    $controller = new UrlController($_GET);
    $controller->redirectToLongUrl();
    break;
  }
  case "POST": {
    $controller = new UrlController($_POST);
    $controller->shortenUrl();
    break;
  }
  default: {
    // Method not allowed
    http_response_code(405);
  }
}
