<?php

/**
 * List of years.
 */
class Controller_Datemap extends Controller_Index {

  protected function getSourceFiles(Request $request): Iterator {
    $rootDir = $this->getRootDir();
    $it = new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isFile()
          && preg_match('/\d{4}-\d-\d+\.xml$/', $file);
    });
    yield from $it;
  }

  protected function load(Request $request): JsonResponse {
    $weekFiles = $this->getSourceFiles($request);
    $data = $this->gatherDates($weekFiles);
    $data = iterator_to_array($data, true);
    return new JsonResponse($data);
  }

  private function gatherDates(Iterator $weekFiles): Iterator {
    foreach ($weekFiles as $weekFile) {
      // Sunday
      $sunday = Data_Week::from($weekFile->getPathInfo());
      yield $sunday->date => $sunday;

      // Monday..Friday
      foreach (range(2, 6) as $day) {
        $day = Data_Day::from($weekFile->getPathInfo(), $day);
        $date = new Date($day->date);
        yield "$date" => $day;
      }

      // Sabbath
      $sabbath = Data_Day::from($weekFile->getPathInfo(), 7);
      yield $sabbath->date => Data_Day::from($weekFile->getPathInfo(), 7);
    }
  }

}
