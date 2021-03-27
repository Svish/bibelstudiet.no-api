<?php

class Error_Http extends Exception {

  protected int $httpStatus;

  public function __construct(
    int $httpStatus = null,
    string $message = null,
    Exception $cause = null,
    int $code = E_USER_ERROR
  ) {
    if ($httpStatus === null)
      $httpStatus = 500;

    if ($message === null)
      $message = HTTP::code($httpStatus);

    parent::__construct($message, $code, $cause);
    $this->httpStatus = $httpStatus;
  }

  public function getHttpStatus(): int {
    return $this->httpStatus;
  }

}
