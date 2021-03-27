<?php

/**
 * A year and its quarters.
 */
final class Controller_Year extends Controller_Base {

  use Cache_Get;

  /**
   * @return SplFileInfo /<year>
   */
  private function getYearDir(Request $request): SplFileInfo {
    return $this->getContentDir(intval($request->year));
  }

  /**
   * @return SplFileInfo /<year>/<quarters>/*.*
   */
  protected function getSourceFiles(Request $request): Iterator {
    $yearDir = $this->getYearDir($request);
    foreach(Data_YearPlus::getQuarterDirs($yearDir) as $quarterDir)
      yield from Data_Quarter::getQuarterFiles($quarterDir);
  }

  protected function load(Request $request): JsonResponse {
    $yearDir = $this->getYearDir($request);
    $year = new Data_YearPlus($yearDir);
    return new JsonResponse($year);
  }
}
