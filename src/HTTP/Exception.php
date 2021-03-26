<?php

class HTTP_Exception extends Exception {

	protected int $httpStatus = 500;

	public function __construct(string $message, int $httpStatus = 500, Exception $cause = null, int $code = E_USER_ERROR) {
		parent::__construct($message, $code, $cause);
		$this->httpStatus = $httpStatus;
	}

	public function getHttpStatus(): int {
		return $this->httpStatus;
	}

}
