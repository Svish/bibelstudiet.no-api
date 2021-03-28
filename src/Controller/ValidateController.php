<?php

namespace Bibelstudiet\Controller;

use DateInterval;
use DateTime;
use Iterator;
use SplFileInfo;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use CallbackFilterIterator;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Data\DayData;
use Bibelstudiet\Data\WeekData;
use Bibelstudiet\Date;
use Bibelstudiet\Error\HttpError;

/**
 * Validate all data files.
 */
class ValidateController extends Controller {

  protected function getFiles(Request $request): Iterator {
    $rootDir = $this->getContentDir();
    $it = new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isFile() && $file->getExtension() === 'xml';
    });
    yield from $it;
  }

  public function get(Request $request): JsonResponse {
    throw new HttpError(501);
    $weekFiles = $this->getFiles($request);
    $dates = $this->gather($weekFiles);
    $dates = $this->verifyDates($dates);
    // TODO: Make this work somehow...
    // yield $error messages?
    return new JsonResponse($dates);
  }

  private function gather(Iterator $weekFiles): Iterator {
    foreach ($weekFiles as $weekFile) {
      // Sunday
      $sunday = WeekData::from($weekFile->getPathInfo());
      yield $sunday->date => $sunday;

      // Monday..Friday
      foreach (range(2, 6) as $day) {
        $day = DayData::from($weekFile->getPathInfo(), $day);
        $date = new Date($day->date);
        yield "$date" => $day;
      }

      // Sabbath
      $sabbath = DayData::from($weekFile->getPathInfo(), 7);
      yield $sabbath->date => DayData::from($weekFile->getPathInfo(), 7);
    }
  }

  private function verifyDates(Iterator $data): Iterator {
    $data = iterator_to_array($data, true);
    $dates = array_keys($data);
    $min = array_reduce($dates, 'min', $dates[0]);
    $max = array_reduce($dates, 'max');

    yield ['min' => $min, 'max' => $max];

    for ($date = new DateTime($min); $date->format('Y-m-d') < $max; $date->add(new DateInterval('P1D'))) {
      if (!array_key_exists($date->format('Y-m-d'), $data))
        yield $date->format('Y-m-d');
    }
  }

}
