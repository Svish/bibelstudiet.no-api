<?php

class Error_Developer extends Error_Http {
  public function __construct(string $message, Exception $cause = null) {
    parent::__construct(500, $message, $cause);
  }
}
