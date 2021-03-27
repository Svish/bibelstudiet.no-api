<?php

final class Request {
  private string $path;
  private array $params;

  public function __construct(string $path, array $params) {
    $this->path = $path;
    $this->params = $params;
  }

  public function getPath(): string {
    return $this->path;
  }

  public function __isset($param): bool {
    return array_key_exists($param, $this->params);
  }

  public function __get($param) {
    return $this->params[$param];
  }
}
