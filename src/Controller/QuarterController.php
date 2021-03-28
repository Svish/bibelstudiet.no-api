<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Data\QuarterDataPlus;
use Bibelstudiet\Data\WeekData;

/**
 * A quarter and its weeks.
 */
final class QuarterController extends Controller {

  use CachedGet;

  /**
   * @return SplFileInfo /<year>/<quarter>
   */
  final protected function getQuarterDir(Request $request): SplFileInfo {
    return $this->getContentDir(
      intval($request->year),
      intval($request->quarter)
    );
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/*.* & <week>/*.xml
   */
  protected function getSourceFiles(Request $request): Iterator {
    $quarterDir = $this->getQuarterDir($request);

    yield from QuarterDataPlus::getQuarterFiles($quarterDir);

    foreach(QuarterDataPlus::getWeekDirs($quarterDir) as $weekDir)
      yield from WeekData::getWeekFiles($weekDir);
  }

  protected function load(Request $request): JsonResponse {
    $quarterDir = $this->getQuarterDir($request);
    $quarter = new QuarterDataPlus($quarterDir);
    return new JsonResponse($quarter);
  }
}
