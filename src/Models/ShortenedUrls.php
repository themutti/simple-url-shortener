<?php
declare(strict_types=1);

namespace MVC\Models;

class ShortenedUrls extends Database {
  public function getLongUrl(string $shortenedUrl): ?ShortenedUrl {
    $stmt = $this->conn->prepare("SELECT * FROM ShortenedUrls WHERE ShortenedUrl = ?");
    $stmt->execute([$shortenedUrl]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    if (!$row) {
      return null;
    }
    $stmt = null;
    return new ShortenedUrl($row["ID"], $row["ShortenedUrl"], $row["LongUrl"]);
  }

  public function insertUrl(string $shortenedUrl, string $longUrl): void {
    try {
      $stmt = $this->conn->prepare("INSERT INTO ShortenedUrls (ShortenedUrl, LongUrl) VALUES (?, ?)");
      $stmt->execute([$shortenedUrl, $longUrl]);
      $stmt = null;
    } catch (\PDOException $e) {
      // Ingore duplicate entry error
      if ($e->getCode() === "23000") {
        return;
      }
      throw $e;
    }
  }
}
