<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Content;
use Bibelstudiet\Data\WeekData;
use Bibelstudiet\Data\WeekDataPlus;

/**
 * A week and its days.
 */
class WeekController {

  use CachedGet;

  /**
   * @return SplFileInfo /<year>/<quarter>/<week>
   */
  final protected function getWeekDir(Request $request): SplFileInfo {
    return Content::getDir(
      intval($request->year),
      intval($request->quarter),
      intval($request->week)
    );
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.xml
   */
  protected function getDataSources(Request $request): Iterator {
    $weekDir = $this->getWeekDir($request);
    yield from WeekData::getWeekFiles($weekDir);
  }

  protected function load(Request $request): JsonResponse {
    $weekDir = $this->getWeekDir($request);
    $week = new WeekDataPlus($weekDir);
    return new JsonResponse($week);
  }
}
