<?php

namespace Bibelstudiet\Error;

class DeveloperError extends HttpError {
  public function __construct(string $message, \Exception $cause = null) {
    parent::__construct(500, $message, $cause);
  }
}
