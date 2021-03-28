<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

/**
 * The index with its list of years.
 */
class IndexData extends DirectoryData {

  /**
   * @return Iterator Yields data about year.
   */
  protected function gatherData(SplFileInfo $rootDir): Iterator {
    yield 'years' => mapToArray(
      static::getYearDirs($rootDir),
      function (SplFileInfo $yearDir) {
        return new YearData($yearDir);
      }
    );
  }

  /**
   * @return Iterator /<years>
   */
  public static final function getYearDirs(SplFileInfo $rootDir): Iterator {
    $it = new FilesystemIterator($rootDir);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isDir()
        && preg_match('/^\d{4}$/', $file->getFilename());
    });
    return $it;
  }

}
