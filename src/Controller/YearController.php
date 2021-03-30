<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;
use Bibelstudiet\Data\QuarterData;
use Bibelstudiet\Data\YearDataPlus;

/**
 * A year and its quarters.
 */
final class YearController extends CachedController {

  /**
   * @return SplFileInfo /<year>
   */
  private function getYearDir(): SplFileInfo {
    return Content::getDir(intval($this->request->year));
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/*.*
   */
  protected function getDataSources(): Iterator {
    $yearDir = $this->getYearDir();
    foreach(YearDataPlus::getQuarterDirs($yearDir) as $quarterDir)
      yield from QuarterData::getQuarterFiles($quarterDir);
  }

  protected function getResponse(): JsonResponse {
    $yearDir = $this->getYearDir();
    $year = new YearDataPlus($yearDir);
    return new JsonResponse($year);
  }
}
