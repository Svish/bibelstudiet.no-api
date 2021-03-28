<?php

namespace Bibelstudiet\Controller;

use Iterator;

use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Data\DayDataPlus;

/**
 * A day.
 */
final class DayController extends WeekController {

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.*
   */
  protected function getSourceFiles(Request $request): Iterator {
    $weekDir = $this->getWeekDir($request);
    yield from DayDataPlus::getDayFiles($weekDir, $request->day);
  }

  protected function load(Request $request): JsonResponse {
    $weekDir = $this->getWeekDir($request);
    $week = new DayDataPlus($weekDir, $request->day);
    return new JsonResponse($week);
  }
}
