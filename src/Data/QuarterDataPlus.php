<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use CallbackFilterIterator;

use Bibelstudiet\Xml;

/**
 * A quarter and its weeks.
 */
class QuarterDataPlus extends QuarterData {

  /**
   * @return Iterator Adds more details and list of weeks.
   */
  protected function gatherData(SplFileInfo $quarterDir): Iterator {
    [, $year, $quarter] = $this->parsePath($quarterDir);

    yield from parent::gatherData($quarterDir);

    foreach ($this->getQuarterFiles($quarterDir) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);

          $credits = $xml->query('/quarter/credits')->item(0);
          yield 'meta' => [
            'title' => $xml->string('title', $credits),
            'author' => [
              'name' => $xml->string('author', $credits),
            ],
            'editor' => [
              'name' => $xml->string('editor', $credits),
              'email' => $xml->string('editor/@email', $credits),
            ],
            'translator' => [
              'name' => $xml->string('translator', $credits),
              'email' => $xml->string('translator/@email', $credits),
            ],
          ];

          yield 'forword' => ['xml' => $xml->toString('/quarter/forword')];
          break;
        }

        case 'pdf': {
          yield 'pdf' => [
            'url' => WEBROOT."$year/$quarter.pdf",
            'size' => $file->getSize(),
          ];
          break;
        }
      }

      yield 'weeks' => mapToArray(
        $this->getWeekDirs($quarterDir),
        function (SplFileInfo $weekDir) {
          return new WeekData($weekDir);
        }
      );
  }

  /**
   * @return Iterator /<year>/<quarter>/<week>/*.xml
   */
  public static final function getWeekDirs(SplFileInfo $quarterDir): Iterator {
    $it = new RecursiveDirectoryIterator($quarterDir, FilesystemIterator::SKIP_DOTS);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isDir();
    });
    return $it;
  }
}
