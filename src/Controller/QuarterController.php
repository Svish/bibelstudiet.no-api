<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;
use Bibelstudiet\Data\QuarterDataPlus;
use Bibelstudiet\Data\WeekData;

/**
 * A quarter and its weeks.
 */
final class QuarterController extends CachedController {

  /**
   * @return SplFileInfo /<year>/<quarter>
   */
  final protected function getQuarterDir(): SplFileInfo {
    return Content::getDir(
      intval($this->request->year),
      intval($this->request->quarter)
    );
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/*.* & <week>/*.xml
   */
  protected function getDataSources(): Iterator {
    $quarterDir = $this->getQuarterDir();

    yield from QuarterDataPlus::getQuarterFiles($quarterDir);

    foreach(QuarterDataPlus::getWeekDirs($quarterDir) as $weekDir)
      yield from WeekData::getWeekFiles($weekDir);
  }

  protected function getResponse(): JsonResponse {
    $quarterDir = $this->getQuarterDir();
    $quarter = new QuarterDataPlus($quarterDir);
    return new JsonResponse($quarter);
  }
}
