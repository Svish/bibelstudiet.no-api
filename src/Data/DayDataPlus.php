<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;

use getID3;

use Bibelstudiet\Xml;

/**
 * A week and its days.
 */
class DayDataPlus extends DayData {

  /**
   * @return Iterator Yields data about day.
   */
  protected function gatherData(SplFileInfo $weekDir, int $day): Iterator {
    yield from parent::gatherData($weekDir, $day);

    foreach ($this->getDayFiles($weekDir, $day) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);
          switch($day) {
            case 0:
              $node = $xml->query('/week/introduction')->item(0);
              yield 'introduction' => ['xml' => $xml->toString($node)];
              break;

            case 7:
              $node = $xml->query('/week/story')->item(0);
              yield 'story' => ['xml' => $xml->toString($node)];
              break;

            default:
              $node = $xml->query('/week/day')->item($day-1);
              yield 'study' => ['xml' => $xml->toString($node)];
              break;
          }

          break;
        }

        case 'mp3': {
          $lib = new getID3;
          $id3 = $lib->analyze($file);

          yield 'audio' => [
            'url' =>  cleanPath($file),
            'size' => $file->getSize(),
            'bitrate' => $id3['bitrate'],
            'playtime' => [
              'string' => $id3['playtime_string'],
              'seconds' => $id3['playtime_seconds'],
            ],
          ];
        }
    }
  }
}
