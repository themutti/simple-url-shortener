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

  protected static function sanitizeLongUrl(string $longUrl): string|false {
    $loweredUrl = strtolower($longUrl);
    if (!str_starts_with($loweredUrl, "http://") && !str_starts_with($loweredUrl, "https://")) {
      $longUrl = "https://" . $longUrl;
    }
    return filter_var($longUrl, FILTER_VALIDATE_URL);
  }

  protected static function outputJson(string $shortenedUrl): void {
    echo json_encode(array("url" => $shortenedUrl));
  }

  protected function generateShortUrl(): string|false {
    $shortUrl = "";
    $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
    for ($i = 0; $i < 20; $i++) {  // Max 20 attempts
      for ($j = 0; $j < 6; $j++) {  // Shortened URL is 6 chars long
        $shortUrl .= $chars[rand(0, strlen($chars) - 1)];
      }
      $record = $this->db->fetchByShortenedUrl($shortUrl);
      if (!$record) {
        return $shortUrl;
      }
      $shortUrl = "";
    }
    return false;
  }

  public function shortenUrl(): void {
    header("Content-Type: application/json; charset=utf-8");
    $longUrl = @$this->request["url"];
    // Check if the long URL is present
    if (!isset($longUrl)) {
      http_response_code(400);
      return;
    }
    // Sanitize and validate the long URL
    $longUrl = UrlController::sanitizeLongUrl($longUrl);
    if ($longUrl === false) {
      http_response_code(400);
      return;
    }
    // Check if the long URL is already in the db
    $record = $this->db->fetchByLongUrl($longUrl);
    if ($record) {
      UrlController::outputJson($record->getShortenedUrl());
      return;
    }
    // Otherwise generate a new shortened URL
    $shortUrl = $this->generateShortUrl();
    if ($shortUrl === false) {
      http_response_code(500);
      return;
    }
    $this->db->insertUrl($shortUrl, $longUrl);
    UrlController::outputJson($shortUrl);
  }

  public function redirectToLongUrl(): void {
    $shortUrl = @$this->request["url"];
    $shortenedUrl = null;
    if (isset($shortUrl)) {
      $shortenedUrl = $this->db->fetchByShortenedUrl($shortUrl);
    }
    $longUrl = $shortenedUrl ? $shortenedUrl->getLongUrl() : "/";
    header("Location: $longUrl");
    http_response_code(302);
  }
}
