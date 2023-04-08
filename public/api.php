<?php
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . "/../app/Models/ShortenedUrl.php";

use App\Models\ShortenedUrls;

$db = new ShortenedUrls("localhost", "UrlShortenerDb", "root", "");
var_dump($db->insertUrl("bbbbab", "www.minecraft.net"));
$data = $db->getLongUrl("aaaaaa");
var_dump($data);
$db->close();
