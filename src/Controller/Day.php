<?php

/**
 * A day.
 */
final class Controller_Day extends Controller_Week {

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.*
   */
  protected function getSourceFiles(Request $request): Iterator {
    $weekDir = $this->getWeekDir($request);
    yield from Data_DayPlus::getDayFiles($weekDir, $request->day);
  }

  protected function load(Request $request): JsonResponse {
    $weekDir = $this->getWeekDir($request);
    $week = new Data_DayPlus($weekDir, $request->day);
    return new JsonResponse($week);
  }
}
