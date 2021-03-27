<?php

/**
 * A week and its days.
 */
class Controller_Week extends Controller_Base {

  use Cache_Get;

  /**
   * @return SplFileInfo /<year>/<quarter>/<week>
   */
  final protected function getWeekDir(Request $request): SplFileInfo {
    return $this->getContentDir(
      intval($request->year),
      intval($request->quarter),
      intval($request->week)
    );
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/<week>/*.xml
   */
  protected function getSourceFiles(Request $request): Iterator {
    $weekDir = $this->getWeekDir($request);
    yield from Data_Week::getWeekFiles($weekDir);
  }

  protected function load(Request $request): JsonResponse {
    $weekDir = $this->getWeekDir($request);
    $week = new Data_WeekPlus($weekDir);
    return new JsonResponse($week);
  }
}
