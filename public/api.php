<?php
declare(strict_types=1);

use MVC\Models\Database;

require_once "../src/Models/Database.php";

$db = new Database("localhost", "UrlShortenerDb", "root", "");
$db->close();
