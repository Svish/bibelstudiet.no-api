<?php

namespace Bibelstudiet\Api;

final class Api {
  private Router $router;

  public function __construct(array $routes) {
    $this->router = new Router($routes);
  }

  public function serve(string $method, string $path): void {
    // TODO: Limit to configured hosts
    header('Access-Control-Allow-Origin: *');

    $response = $this->router->run($method, $path);
    $response->flush();
  }

}
