<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;
use Bibelstudiet\Data\RootData;

/**
 * List of years.
 */
class RootController extends CachedController {

  /**
   * @return SplFileInfo /
   */
  final protected function getRootDir(): SplFileInfo {
    return Content::getDir();
  }

  protected function getDataSources(): Iterator {
    $rootDir = $this->getRootDir();
    return RootData::getYearDirs($rootDir);
  }

  protected function getResponse(): JsonResponse {
    $rootDir = $this->getRootDir();
    $root = new RootData($rootDir);
    return new JsonResponse($root);
  }
}
