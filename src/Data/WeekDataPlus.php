<?php

namespace Bibelstudiet\Data;

use Iterator;
use DOMElement;
use SplFileInfo;

use Bibelstudiet\Xml;

/**
 * A week and its days.
 */
class WeekDataPlus extends WeekData {

  /**
   * @return Iterator Adds more details and list of days.
   */
  protected function gatherData(SplFileInfo $weekDir): Iterator {
    yield from parent::gatherData($weekDir);

    foreach ($this->getWeekFiles($weekDir) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);

          yield 'background' => array_map(function(DOMElement $r) {
            return $r->textContent;
          }, $xml->queryArray('/week/introduction/background/r'));

          yield 'memory' => $xml->transformToString('/week/introduction/memory');

          break;
        }
      }

    yield 'days' => array_map(function($key) use($weekDir) {
      return new DayData($weekDir, $key);
    }, range(0, 7));
  }

}
