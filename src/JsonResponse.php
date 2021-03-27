<?php

class JsonResponse implements Response {
  private $data;

  public function __construct($data) {
    $this->data = $data;
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
