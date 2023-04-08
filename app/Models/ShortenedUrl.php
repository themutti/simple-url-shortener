<?php
declare(strict_types=1);

namespace App\Models;

class ShortenedUrl {
  private int $id;
  private string $shortenedUrl;
  private string $longUrl;

  public function __construct(int $id, string $shortenedUrl, string $longUrl) {
    $this->id = $id;
    $this->shortenedUrl = $shortenedUrl;
    $this->longUrl = $longUrl;
  }

  public function getId(): int {
    return $this->id;
  }

  public function getShortenedUrl(): string {
    return $this->shortenedUrl;
  }

  public function getLongUrl(): string {
    return $this->longUrl;
  }
}
