<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ShortenedUrls;

class UrlController {
  protected array $request;
  protected ShortenedUrls $db;

  public function __construct(array $request) {
    $this->request = $request;
    $this->db = UrlController::loadModel();
  }

  protected static function loadModel(): ShortenedUrls {
    $iniData = parse_ini_file(__DIR__ . "/../../config/credentials.ini", true);
    if (!$iniData) {
      throw new \Exception("Failed to load database credentials from \"config/credentials.ini\".");
    }
    $creds = $iniData["database"];
    return new ShortenedUrls(
      $creds["host"],
      $creds["dbname"],
      $creds["username"],
      $creds["password"]
    );
  }

  public function redirectToLongUrl(): void {
    $shortUrl = $this->request["url"];
    $shortenedUrl = null;
    if (isset($shortUrl)) {
      $shortenedUrl = $this->db->getLongUrl($shortUrl);
    }
    $longUrl = $shortenedUrl ? $shortenedUrl->getLongUrl() : "/";
    header("Location: $longUrl");
    http_response_code(302);
  }
}
