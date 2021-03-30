<?php

namespace Bibelstudiet\Api;

use Bibelstudiet\Error\NotFoundError;
use Bibelstudiet\Error\HttpError;

final class Api {
  private array $routes;

  public function __construct(array $routes) {
    $this->routes = $routes;
  }

  public function serve(string $method, string $path): void {
    $path = '/'.trim($path, '/');
    $path = parse_url($path);
    $path = urldecode($path['path']);

    $method = strtolower($method);
    foreach ($this->routes as $pattern => $handler) {
      if ( ! preg_match("#^$pattern$#u", $path, $params))
        continue;

      $request = new Request($method, array_shift($params), $params);

      try {
        $handler = new $handler($request);
        $method = new \ReflectionMethod($handler, $request->getMethod());
        $method->invoke($handler);
        return;
      } catch (\ReflectionException $e) {
        throw new HttpError(405, null, $e);
      }
    }

    throw new NotFoundError("Path '$path' not found");
  }
}
