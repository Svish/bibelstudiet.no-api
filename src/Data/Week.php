<?php

/**
 * A week.
 */
class Data_Week extends Data_Base {

  /**
   * @return Generator Yields data about week.
   */
  protected function gatherData(SplFileInfo $weekDir): Generator {
    [, $year, $quarter, $week] = $this->parsePath($weekDir);
    yield 'type' => 'week';
    yield 'url' => "$year/$quarter/$week";
    yield 'name' => "Uke $week";

    foreach ($this->getWeekFiles($weekDir) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);
          yield 'title' => $xml->string('/week/title');

          $sabbath = new Date($xml->string('/week/@sabbath'));
          yield 'sabbath' => "$sabbath";

          $sunday = $sabbath->subDays(6);
          yield 'date' => "$sunday";
          break;
        }
      }
  }

  protected final function parsePath(SplFileInfo $weekDir): array {
    return Regex::pathMatch($weekDir,
    '%(?<year>\d+)/(?<quarter>\d+)/(?<week>\d+)$%'
  );
  }

  /**
   * @return Iterator /<year>/<quarter>/*.*
   */
  public static final function getWeekFiles(SplFileInfo $weekDir): Iterator {

    $it = new FilesystemIterator($weekDir);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isFile() && $file->getExtension() === 'xml';
    });
    return $it;
  }
}
