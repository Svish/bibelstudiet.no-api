<?php

/**
 * A quarter and its weeks.
 */
class Data_YearPlus extends Data_Year {

  /**
   * @return Generator Yields data about year.
   */
  protected function gatherData(SplFileInfo $yearDir): Generator {
    yield from parent::gatherData($yearDir);
    yield 'quarters' => mapToArray($this->getQuarterDirs($yearDir), ['Data_Quarter', 'from']);
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
