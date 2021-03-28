<?php

namespace Bibelstudiet;

use Bibelstudiet\Error\NotFoundError;

use SplFileInfo;

final class Regex {

  public static function matches(string $pattern, string $subject): array {
    if( ! preg_match($pattern, $subject, $matches))
      throw new NotFoundError("Pattern '$pattern' does not match '$subject'");

    return $matches;
  }

  public static function pathMatch(SplFileInfo $path, string $pattern): array {
    return self::matches($pattern, cleanPath($path));
  }

}
