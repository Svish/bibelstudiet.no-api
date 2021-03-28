<?php

namespace Bibelstudiet\Api;

use Iterator;
use JsonSerializable;

class JsonResponse implements Response, JsonSerializable {
  protected $data;

  public function __construct($data) {
    $this->data = $data;
  }

  public function jsonSerialize() {
    return $this->data;
  }

  public function __isset($key): bool {
    return array_key_exists($key, $this->data);
  }

  public function __get($data) {
    return $this->data[$data];
  }

  public function flush(): void {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($this->data);
  }

  public static function debug($data): void {
    if ($data instanceof Iterator)
      $data = iterator_to_array($data, true);
    (new static($data))->flush();
    exit;
  }
}
