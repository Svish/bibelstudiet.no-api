<?php

namespace Bibelstudiet\Cache;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\Response;
use Bibelstudiet\Controller\Controller;

abstract class CachedController extends Controller {

  /**
   * List directories and files current request depends upon.
   */
  protected abstract function getDataSources(): Iterator;

  /**
   * Create response for current request.
   */
  protected abstract function getResponse(): Response;


  public final function get(): Response {
    // For debugging source file paths
    if (isset($_GET['sources']))
      return new JsonResponse(map($this->getDataSources(),
        function(SplFileInfo $file) {
          return cleanPath($file);
        }
      ));

    // Create cache key
    $key = $this->request->getPath();

    if ($this instanceof CacheParameters) {
      $get = array_whitelist($_GET, $this->getCacheParameters());
      $get = json_encode($get, JSON_NUMERIC_CHECK);
      $key .= $get;
    }

    // Get cache key
    $cache = Cache::init(get_called_class())->key($key);

    // Try get data from cache
    try {
      $data = $cache->get();

      $mtime = max(
        static::getMTime(iterate_included_files()),
        static::getMTime($this->getDataSources())
      );

      if($mtime >= $cache->getMTime()) {
        header('X-Cache-Hit: expired');
      } else {
        header('X-Cache-Hit: hit');
        return $data;
      }
    } catch (\Throwable $e) {
      // Cache file missing or incompatible with classes
      header('X-Cache-Hit: miss');
    }

    // Get data regular way
    $data = $this->getResponse();
    $cache->set($data);
    return $data;
  }

  private final static function getMTime(iterable $files) {
    $mtimes = map($files, function(SplFileInfo $file) {
      return $file->getMTime();
    });

    return reduce($mtimes, 0, 'max');
  }
}
