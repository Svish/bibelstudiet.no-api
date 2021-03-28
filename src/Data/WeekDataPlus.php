<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;

/**
 * A week and its days.
 */
class WeekDataPlus extends WeekData {

  /**
   * @return Iterator Adds more details and list of days.
   */
  protected function gatherData(SplFileInfo $weekDir): Iterator {
    [, $year, $quarter] = $this->parsePath($weekDir);

    yield 'parent' => "$year/$quarter";
    yield from parent::gatherData($weekDir);

    yield 'days' => array_map(function($key) use($weekDir) {
      return new DayData($weekDir, $key);
    }, range(0, 7));

  }

}
