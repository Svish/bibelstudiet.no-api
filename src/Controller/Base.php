<?php

abstract class Controller_Base {

  /**
   * Helper method to create CONTENT paths.
   * @throws Error_NotFound If path is not a directory.
   */
  final protected function getContentDir(...$parts): SplFileInfo {
    $dir = new SplFileInfo(CONTENT.implode(DIRECTORY_SEPARATOR, $parts));

    if ($dir->isDir())
      return $dir;

    $path = cleanPath($dir);
    throw new Error_NotFound("Path '$path' not found");
  }

}
