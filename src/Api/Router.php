<?php

namespace Bibelstudiet\Api;

use Bibelstudiet\Error\NotFoundError;
use Bibelstudiet\Error\HttpError;

final class Router {
  private array $routes;

  public function __construct(array $routes) {
    $this->routes = $routes;
  }

  public function run(string $method, string $path): Response {
    $path = '/'.trim($path, '/');
    $path = parse_url($path);
    $path = urldecode($path['path']);

    $method = strtolower($method);

    foreach ($this->routes as $pattern => $handler) {
      if ( ! preg_match("#^$pattern$#u", $path, $params))
        continue;

      $request = new Request($method, array_shift($params), $params);

      try {
        $handler = new $handler();
        $method = new \ReflectionMethod($handler, $request->getMethod());
        return $method->invoke($handler, $request);
      } catch (\ReflectionException $e) {
        throw new HttpError(405, null, $e);
      }
    }

    throw new NotFoundError("Path '$path' not found");
  }
}
