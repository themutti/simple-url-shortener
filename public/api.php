<?php
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . "/../src/Models/ShortenedUrl.php";

use MVC\Models\ShortenedUrls;

$db = new ShortenedUrls("localhost", "UrlShortenerDb", "root", "");
// $db->insertUrl("bbbbbbb", "www.minecraft.net");
$data = $db->getLongUrl("aaaaaa");
$db->close();
