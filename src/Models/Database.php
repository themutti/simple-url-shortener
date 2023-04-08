<?php
declare(strict_types=1);

namespace MVC\Models;

class Database {
  protected ?\PDO $conn;

  public function __construct(string $host, string $dbname, string $username, string $password) {
    $this->conn = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $username, $password);
    $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public function close(): void {
    $this->conn = null;
  }
}
