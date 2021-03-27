<?php

class Cache_Cache
{
  const DIR = CACHE;
  private $dir;

  private function __construct(string $id) {
    $this->dir = static::DIR.$id.DIRECTORY_SEPARATOR;

    // https://en.wikipedia.org/wiki/Chmod#System_call
    if( ! is_dir($this->dir)) {
      @mkdir($this->dir, 06750, true);
      @chmod($this->dir, 06750);
    }
  }

  public static function init(string $id): self {
    return new static($id);
  }

  public function key(string $key): Cache_File {
    return new Cache_File(new SplFileInfo($this->path($key))) ;
  }

  private function path(string $key) {
    return $this->dir . static::sanitize($key);
  }

  private static function sanitize(string $key) {
    return preg_replace('/[^.a-z0-9_-]+/i', '-', $key);
  }
}
