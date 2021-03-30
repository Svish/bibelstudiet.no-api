<?php

namespace Bibelstudiet\Controller;

use Iterator;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Data\DayDataPlus;

/**
 * A day.
 */
final class DayController extends WeekController {

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.*
   */
  protected function getDataSources(): Iterator {
    $weekDir = $this->getWeekDir();
    yield from DayDataPlus::getDayFiles($weekDir, $this->request->day);
  }

  protected function getResponse(): JsonResponse {
    $weekDir = $this->getWeekDir();
    $week = new DayDataPlus($weekDir, $this->request->day);
    return new JsonResponse($week);
  }
}
