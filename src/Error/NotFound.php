<?php

class Error_NotFound extends Error_Http {
  public function __construct(string $message, Exception $cause = null) {
    parent::__construct(404, $message, $cause);
  }
}
