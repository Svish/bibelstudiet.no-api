<?php

abstract class Data implements JsonSerializable {
  private $data;

  protected function __construct($data) {
    $this->data = $data;
  }

  public function jsonSerialize() {
    return $this->data;
  }

  public function pick(string ...$keys): array {
    return array_intersect_key($this->data, array_flip($keys));
  }

  public function omit(string ...$keys): array {
    return array_diff_key($this->data, array_flip($keys));
  }

  public function __isset($key): bool {
    return array_key_exists($key, $this->data);
  }

  public function __get($data) {
    return $this->data[$data];
  }
}
