<?php

/**
 * List of years.
 */
class Controller_Index extends Controller_Base {

  use Cache_Get;

  /**
   * @return SplFileInfo /
   */
  final protected function getRootDir(): SplFileInfo {
    return $this->getContentDir();
  }

  protected function getSourceFiles(Request $request): Iterator {
    $rootDir = $this->getRootDir();
    return Data_Index::getYearDirs($rootDir);
  }

  protected function load(Request $request): JsonResponse {
    $rootDir = $this->getRootDir();
    $index = new Data_Index($rootDir);
    return new JsonResponse($index);
  }
}
