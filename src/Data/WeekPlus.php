<?php

/**
 * A week and its days.
 */
class Data_WeekPlus extends Data_Week {

  /**
   * @return Generator Adds more details and list of days.
   */
  protected function gatherData(SplFileInfo $weekDir): Generator {
    [, $year, $quarter] = $this->parsePath($weekDir);

    yield 'parent' => "$year/$quarter";
    yield from parent::gatherData($weekDir);

    yield 'days' => array_map(function($key) use($weekDir) {
      return Data_Day::from($weekDir, $key);
    }, range(0, 7));

  }

}
