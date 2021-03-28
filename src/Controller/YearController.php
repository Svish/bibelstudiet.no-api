<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Content;
use Bibelstudiet\Data\QuarterData;
use Bibelstudiet\Data\YearDataPlus;

/**
 * A year and its quarters.
 */
final class YearController {

  use CachedGet;

  /**
   * @return SplFileInfo /<year>
   */
  private function getYearDir(Request $request): SplFileInfo {
    return Content::getDir(intval($request->year));
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/*.*
   */
  protected function getDataSources(Request $request): Iterator {
    $yearDir = $this->getYearDir($request);
    foreach(YearDataPlus::getQuarterDirs($yearDir) as $quarterDir)
      yield from QuarterData::getQuarterFiles($quarterDir);
  }

  protected function load(Request $request): JsonResponse {
    $yearDir = $this->getYearDir($request);
    $year = new YearDataPlus($yearDir);
    return new JsonResponse($year);
  }
}
