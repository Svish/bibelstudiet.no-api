<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

/**
 * A quarter and its weeks.
 */
class YearDataPlus extends YearData {

  /**
   * @return Iterator Yields data about year.
   */
  protected function gatherData(SplFileInfo $yearDir): Iterator {
    yield from parent::gatherData($yearDir);
    yield 'quarters' => mapToArray(
      $this->getQuarterDirs($yearDir),
      function (SplFileInfo $quarterDir) {
        return new QuarterData($quarterDir);
      }
    );
  }

  /**
   * @return Iterator /<year>/<quarters>
   */
  public static final function getQuarterDirs(SplFileInfo $yearDir): Iterator {
    $it = new FilesystemIterator($yearDir);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isDir() && preg_match('/1|2|3|4/', $file->getFilename());
    });
    return $it;
  }

}
