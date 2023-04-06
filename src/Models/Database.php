<?php
declare(strict_types=1);

namespace MVC\Models;

use \MVC\Exceptions\DbConnectException;

class Database {
  protected ?\PDO $conn;

  public function __constructor(string $host, string $dbname, string $username, string $password) {
    if (empty($host) || empty($dbname) || empty($username) || empty($password)) {
      throw new DbConnectException("Bad parameters");
    }
    try {
      $this->conn = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $username, $password);
      $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch(\PDOException $e) {
      throw new DbConnectException("Error while connecting", $e->getCode(), $e);
    }
  }

  public function close(): void {
    $this->conn = null;
  }
}
