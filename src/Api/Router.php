<?php

namespace Bibelstudiet\Api;

use Bibelstudiet\Error\NotFoundError;
use Bibelstudiet\Error\DeveloperError;
use Bibelstudiet\Error\HttpError;

final class Router {
  private $basepath;
  private $routes = [];

  public function __construct(string $basepath) {
    $this->basepath = rtrim($basepath, '/');
  }

  /**
   * @param $route Regular expression to match route
   * @param string $handler Callable function or name of class to handle request
   */
  public function add(string $route, string $handler): self {
    array_push($this->routes, [$route, $handler]);
    return $this;
  }

  /**
   * @return mixed Returns response from route handler.
   */
  public function run() {
    $url = $_SERVER['REQUEST_URI'];
    $url = substr($url, strlen($this->basepath));
    $url = '/'.trim($url, '/');
    $url = parse_url($url);

    $path = urldecode($url['path']);
    $method = strtolower($_SERVER['REQUEST_METHOD']);

    foreach ($this->routes as $route) {
      [$pattern, $handler] = $route;

      if ( ! preg_match("#^$pattern$#u", $path, $params))
        continue;

      return $this->call($handler, $method, $params);
    }

    throw new NotFoundError("Path '$path' not found");
  }

  private function call($handler, string $method, array $params) {
    if (!class_exists($handler))
      throw new DeveloperError("Handler not found: $handler");

    $handler = [new $handler(), $method];

    try {
      $method = new \ReflectionMethod(...$handler);
    } catch (\ReflectionException $e) {
      throw new HttpError(405, null, $e);
    }

    $path = array_shift($params);
    return $handler(new Request($path, $params));
  }
}
