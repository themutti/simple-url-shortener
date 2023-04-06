<?php
declare(strict_types=1);

namespace MVC\Exceptions;

class DatabaseException extends \Exception {
  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}

class DbConnectException extends DatabaseException {
  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct("Failed to connect to the database. $message", $code, $previous);
  }
}

class DbQueryException extends DatabaseException {
  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct("Failed to query the database. $message", $code, $previous);
  }
}
