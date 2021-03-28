<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Data\IndexData;

/**
 * List of years.
 */
class IndexController extends Controller {

  use CachedGet;

  /**
   * @return SplFileInfo /
   */
  final protected function getRootDir(): SplFileInfo {
    return $this->getContentDir();
  }

  protected function getDataSources(Request $request): Iterator {
    $rootDir = $this->getRootDir();
    return IndexData::getYearDirs($rootDir);
  }

  protected function load(Request $request): JsonResponse {
    $rootDir = $this->getRootDir();
    $index = new IndexData($rootDir);
    return new JsonResponse($index);
  }
}
