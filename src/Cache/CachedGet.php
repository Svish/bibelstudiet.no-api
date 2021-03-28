<?php

namespace Bibelstudiet\Cache;

use Iterator;
use ArrayIterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\Response;

trait CachedGet {
  /**
   * GET parameters to include in cache key.
   */
	protected $parameter_whitelist = [];

  /**
   * Creates the response which will be cached by this trait.
   */
  protected abstract function load(Request $request): Response;

  /**
   * Iterates over data sources (dirs/files) which should possible expire the cache.
   */
  protected abstract function getDataSources(Request $request): Iterator;


  public function get(Request $request): Response {
    // For debugging source file paths
    if (isset($_GET['sources']))
      return new JsonResponse(mapToArray($this->getDataSources($request),
        function(SplFileInfo $file) {
          return cleanPath($file);
        }
      ));

    // Create cache key
    $get = array_whitelist($_GET, $this->parameter_whitelist);
    $get = json_encode($get, JSON_NUMERIC_CHECK);
    $key = $request->getPath().$get;

    // Get cache key
    $cache = Cache::init(get_called_class())->key($key);

    // Try get data from cache
    try {
      $data = $cache->get();

      $mtime = max(
        static::getMTimeOfIncludedFiles(),
        static::getMTime($this->getDataSources($request))
      );

      if($cache->getMTime() < $mtime) {
        header('X-Cache-Hit: expired');
      } else {
        header('X-Cache-Hit: hit');
        return $data;
      }
    } catch (\RuntimeException $e) {
      header('X-Cache-Hit: miss');
    }

    // Get data regular way
    $data = $this->load($request);
    $cache->set($data);
    return $data;
  }

  private final static function getMTimeOfIncludedFiles() {
    $files = iterate_included_files();
    return self::getMTime($files);
  }

  private final static function getMTime(Iterator $files) {
    $mtimes = map($files, function(SplFileInfo $file) {
      return $file->getMTime();
    });

    return reduce($mtimes, 0, 'max');
  }
}
