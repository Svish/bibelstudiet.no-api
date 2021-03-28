<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use CallbackFilterIterator;

use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Cache\CachedGet;
use Bibelstudiet\Cache\CacheParameters;
use Bibelstudiet\Content;
use Bibelstudiet\Data\DayData;
use Bibelstudiet\Data\WeekData;
use Bibelstudiet\Date;
use Bibelstudiet\Error\HttpError;
use Bibelstudiet\Error\NotFoundError;

/**
 * Map over all content ids.
 */
class IndexController implements CacheParameters {

  use CachedGet;
  function getCacheParameters(): array {
    return ['type'];
  }

  private $type_filter = [
    'year' => '%^\d+$%',
    'quarter' => '%^\d{4}/\d$%',
    'week' => '%^\d{4}/\d/\d+$%',
  ];

  protected function getDataSources(Request $request): Iterator {
    $rootDir = Content::getDir();
    $it = new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isDir() && is_numeric($file->getFilename());
    });
    yield from $it;
  }

  protected function load(Request $request): JsonResponse {
    $type = $_GET['type'] ?? null;

    if( ! array_key_exists($type, $this->type_filter))
      throw new HttpError(400, 'Missing ?type=year|quarter|week');

    $files = $this->getDataSources($request);
    $data = mapToArray($files, 'cleanPath');
    $data = array_filter($data, function ($path) use ($type) {
      return preg_match($this->type_filter[$type], $path);
    });
    usort($data, 'strnatcmp');
    $data = array_map(function ($path) {
      return explode('/', $path);
    }, $data);

    return new JsonResponse($data);
  }

}
