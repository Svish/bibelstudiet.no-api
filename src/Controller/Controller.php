<?php

namespace Bibelstudiet\Controller;

use Bibelstudiet\Api\Request;

abstract class Controller {
  protected Request $request;

  public function __construct(Request $request) {
    $this->request = $request;
  }
}
