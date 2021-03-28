<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

use Bibelstudiet\Xml;
use Bibelstudiet\Date;
use Bibelstudiet\Regex;

/**
 * A week.
 */
class WeekData extends DirectoryData {

  /**
   * @return Iterator Yields data about week.
   */
  protected function gatherData(SplFileInfo $weekDir): Iterator {
    [, $year, $quarter, $week] = $this->parsePath($weekDir);
    yield 'type' => 'week';
    yield 'id' => [intval($year), intval($quarter), intval($week)];
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
