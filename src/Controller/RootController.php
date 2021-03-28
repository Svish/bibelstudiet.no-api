<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Data\RootData;

/**
 * List of years.
 */
class RootController extends Controller {

  use CachedGet;

  /**
   * @return SplFileInfo /
   */
  final protected function getRootDir(): SplFileInfo {
    return $this->getContentDir();
  }

  protected function getDataSources(Request $request): Iterator {
    $rootDir = $this->getRootDir();
    return RootData::getYearDirs($rootDir);
  }

  protected function load(Request $request): JsonResponse {
    $rootDir = $this->getRootDir();
    $root = new RootData($rootDir);
    return new JsonResponse($root);
  }
}
