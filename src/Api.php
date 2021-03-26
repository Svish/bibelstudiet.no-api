<?php

class Api {
  
  public static function init(): self {
		return new self();
  }

  public function __construct() {

  }

  public function serve(): void {
    Json::output(['hello' => 'api']);
  }
}
