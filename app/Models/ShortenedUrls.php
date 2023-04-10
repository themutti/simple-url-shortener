<?php
declare(strict_types=1);

namespace App\Models;

class ShortenedUrls extends Database {
  // Fetches a record from the db that cointains $url.
  // $isLong indicates whether $url referes to the long url or the shortened url.
  private function fetchUrl(string $url, bool $isLong): ?ShortenedUrl {
    $column = $isLong ? "LongUrl" : "ShortenedUrl";
    $stmt = $this->conn->prepare("SELECT * FROM ShortenedUrls WHERE $column = ?");
    $stmt->execute([$url]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    if (!$row) {
      return null;
    }
    $stmt = null;
    return new ShortenedUrl($row["ID"], $row["ShortenedUrl"], $row["LongUrl"]);
  }

  public function fetchByShortenedUrl(string $shortenedUrl): ?ShortenedUrl {
    return $this->fetchUrl($shortenedUrl, false);
  }

  public function fetchByLongUrl(string $longUrl): ?ShortenedUrl {
    return $this->fetchUrl($longUrl, true);
  }

  public function insertUrl(string $shortenedUrl, string $longUrl): bool {
    try {
      $stmt = $this->conn->prepare("INSERT INTO ShortenedUrls (ShortenedUrl, LongUrl) VALUES (?, ?)");
      $stmt->execute([$shortenedUrl, $longUrl]);
      $stmt = null;
    } catch (\PDOException $e) {
      // Ingore duplicate entry error
      if ($e->getCode() === "23000") {
        return false;
      }
      throw $e;
    }
    return true;
  }
}
