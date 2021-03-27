<?php

/**
 * The index with its list of years.
 */
class Data_Index extends Data_Base {

  /**
   * @return Generator Yields data about year.
   */
  protected function gatherData(SplFileInfo $rootDir): Generator {
    yield 'years' => mapToArray(static::getYearDirs($rootDir), ['Data_Year', 'from']);
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
