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
use Bibelstudiet\Cache\CacheParameters;
use Bibelstudiet\Content;
use Bibelstudiet\Error\HttpError;

/**
 * Map over all content ids.
 */
class IndexController extends CachedController implements CacheParameters {

  function getCacheParameters(): array {
    return ['type'];
  }

  private $type_filter = [
    'year' => '%^\d+$%',
    'quarter' => '%^\d{4}/\d$%',
    'week' => '%^\d{4}/\d/\d+$%',
  ];

  protected function getDataSources(): Iterator {
    $rootDir = Content::getDir();
    $it = new RecursiveDirectoryIterator($rootDir, FilesystemIterator::SKIP_DOTS);
    $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isDir() && is_numeric($file->getFilename());
    });
    yield from $it;
  }

  protected function getResponse(): JsonResponse {
    $type = $_GET['type'] ?? null;

    if( ! array_key_exists($type, $this->type_filter))
      throw new HttpError(400, 'Missing ?type=year|quarter|week');

    $files = $this->getDataSources($this->request);
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
