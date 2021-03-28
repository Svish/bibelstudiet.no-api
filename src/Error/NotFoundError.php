<?php

namespace Bibelstudiet\Error;

class NotFoundError extends HttpError {
  public function __construct(string $message, \Exception $cause = null) {
    parent::__construct(404, $message, $cause);
  }
}
