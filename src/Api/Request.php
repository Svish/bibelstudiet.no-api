<?php

namespace Bibelstudiet\Api;

final class Request {
  private string $method;
  private string $path;
  private array $params;

  public function __construct(string $method, string $path, array $params) {
    $this->method = $method;
    $this->path = $path;
    $this->params = $params;
  }

  public function getMethod(): string {
    return $this->method;
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
