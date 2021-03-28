<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;

/**
 * A year and its quarters.
 */
class YearData extends DirectoryData {

  /**
   * @return Iterator Yields data about year.
   */
  protected function gatherData(SplFileInfo $yearDir): Iterator {
    yield 'type' => 'year';
    yield 'url' => $yearDir->getFilename();
    yield 'name' => $yearDir->getFilename();
  }

}
