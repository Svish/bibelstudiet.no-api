<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Xml;
use Bibelstudiet\Date;
use Bibelstudiet\Regex;

/**
 * A week and its days.
 */
class DayData extends JsonResponse {

  public function __construct(SplFileInfo $weekDir, int $day) {
    $data = $this->gatherData($weekDir, $day);
    parent::__construct($data);
  }

  /**
   * @return Iterator Yields data about day.
   */
  protected function gatherData(SplFileInfo $weekDir, int $day): Iterator {
    [, $year, $quarter, $week] = $this->parsePath($weekDir);
    yield 'url' => "$year/$quarter/$week/$day";

    foreach ($this->getDayFiles($weekDir, $day) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);

          switch($day) {
            case 0: {
              $node = $xml->query('/week/introduction')->item(0);
              yield 'type' => 'introduction';
              yield 'name' => 'Introduksjon';
              break;
            }

            case 7: {
              $node = $xml->query('/week/story')->item(0);
              $date = new Date($xml->string('/week/@sabbath'));
              yield 'type' => 'story';
              yield 'name' => 'Misjonsfortelling';
              yield 'title' => $xml->string('title', $node);
              yield 'date' => "$date";
              break;
            }

            default: {
              $node = $xml->query('/week/day')->item($day-1);
              yield 'type' => 'study';
              yield 'name' => "Studium $day";
              yield 'title' => $xml->string('title', $node);

              $date = new Date($xml->string('/week/@sabbath'));
              $date = $date->subDays(7-$day);
              yield 'date' => "$date";
              break;
            }
          }

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
  public static final function getDayFiles(SplFileInfo $weekDir, int $day): Iterator {
    $it = new FilesystemIterator($weekDir);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) use($day) {
      return $file->isFile()
        && ($file->getExtension() === 'xml' || preg_match("/-$day\.mp3$/", $file));
    });
    return $it;
  }
}
