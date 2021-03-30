<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;
use Bibelstudiet\Data\WeekData;
use Bibelstudiet\Data\WeekDataPlus;

/**
 * A week and its days.
 */
class WeekController extends CachedController {

  /**
   * @return SplFileInfo /<year>/<quarter>/<week>
   */
  final protected function getWeekDir(): SplFileInfo {
    return Content::getDir(
      intval($this->request->year),
      intval($this->request->quarter),
      intval($this->request->week)
    );
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.xml
   */
  protected function getDataSources(): Iterator {
    $weekDir = $this->getWeekDir();
    yield from WeekData::getWeekFiles($weekDir);
  }

  protected function getResponse(): JsonResponse {
    $weekDir = $this->getWeekDir();
    $week = new WeekDataPlus($weekDir);
    return new JsonResponse($week);
  }
}
