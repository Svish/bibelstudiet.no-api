<?php

/**
 * A quarter and its weeks.
 */
final class Controller_Quarter extends Controller_Base {

  use Cache_Get;

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

    yield from Data_QuarterPlus::getQuarterFiles($quarterDir);

    foreach(Data_QuarterPlus::getWeekDirs($quarterDir) as $weekDir)
      yield from Data_Week::getWeekFiles($weekDir);
  }

  protected function load(Request $request): JsonResponse {
    $quarterDir = $this->getQuarterDir($request);
    $quarter = new Data_QuarterPlus($quarterDir);
    return new JsonResponse($quarter);
  }
}
