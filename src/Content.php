<?php

namespace Bibelstudiet;

use SplFileInfo;

use Bibelstudiet\Error\NotFoundError;

final class Content {

  /**
   * Gets path to a content directory.
   *
   * @throws NotFoundError If path is not a directory.
   */
  public static function getDir(...$pathSegments): SplFileInfo {
    $dir = new SplFileInfo(CONTENT.implode(DIRECTORY_SEPARATOR, $pathSegments));

    if ($dir->isDir())
      return $dir;

    $path = cleanPath($dir);
    throw new NotFoundError("Not found");
  }

  /**
   * Gets path to a content file.
   *
   * @throws NotFoundError If path is not a file.
   */
  public static function getFile(...$pathSegments): SplFileInfo {
    $file = new SplFileInfo(CONTENT.implode(DIRECTORY_SEPARATOR, $pathSegments));

    if ($file->isFile())
      return $file;

    $path = cleanPath($file);
    throw new NotFoundError("Not found");
  }

}
