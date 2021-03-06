<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use CallbackFilterIterator;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;
use Bibelstudiet\Data\DayData;
use Bibelstudiet\Data\WeekData;
use Bibelstudiet\Date;

/**
 * Map over all dates and their corresponding lesson.
 */
class DatesController extends CachedController {

  protected function getDataSources(): Iterator {
    $rootDir = Content::getDir();
    $it = new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isFile()
          && preg_match('/\d{4}-\d-\d+\.xml$/', $file);
    });
    yield from $it;
  }

  protected function getResponse(): JsonResponse {
    $weekFiles = $this->getDataSources($this->request);
    $data = $this->gatherDates($weekFiles);
    return new JsonResponse($data);
  }

  private function gatherDates(Iterator $weekFiles): Iterator {
    foreach ($weekFiles as $weekFile) {
      // Sunday
      $sunday = new WeekData($weekFile->getPathInfo());
      yield $sunday->date => $sunday;

      // Monday..Friday
      foreach (range(2, 6) as $day) {
        $day = new DayData($weekFile->getPathInfo(), $day);
        $date = new Date($day->date);
        yield "$date" => $day;
      }

      // Sabbath
      $sabbath = new DayData($weekFile->getPathInfo(), 7);
      yield $sabbath->date => new DayData($weekFile->getPathInfo(), 7);
    }
  }

}
