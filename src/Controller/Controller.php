<?php

namespace Bibelstudiet\Controller;

use SplFileInfo;

use Bibelstudiet\Error\NotFoundError;

abstract class Controller {

  /**
   * Helper method to create CONTENT paths.
   * @throws NotFoundError If path is not a directory.
   */
  final protected function getContentDir(...$parts): SplFileInfo {
    $dir = new SplFileInfo(CONTENT.implode(DIRECTORY_SEPARATOR, $parts));

    if ($dir->isDir())
      return $dir;

    $path = cleanPath($dir);
    throw new NotFoundError("Path '$path' not found");
  }

}
