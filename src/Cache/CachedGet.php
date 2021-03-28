<?php

namespace Bibelstudiet\Cache;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Api\Request;
use Bibelstudiet\Api\Response;

trait CachedGet {
	protected $parameter_whitelist = [];

  public function get(Request $request): Response {
    if (isset($_GET['sources']))
      return new JsonResponse(mapToArray($this->getSourceFiles($request),
        function(SplFileInfo $file) {
          return cleanPath($file);
        }
      ));

    $source_files = $this->getSourceFiles($request);
    $last_modified = $this->getLastModified($source_files);

    $get = array_whitelist($_GET, $this->parameter_whitelist);
    $get = json_encode($get, JSON_NUMERIC_CHECK);
    $key = $request->getPath().$get;
    $cache = Cache::init(get_called_class())->key($key);
    $data = $cache->get($last_modified);

    if ($data !== null) {
      return $data;
    }

    $data = $this->load($request);
    $cache->set($data);
    return $data;
  }

  private function getLastModified(Iterator $files) {
    $mtimes = map($files, function(SplFileInfo $file) {
      return $file->getMTime();
    });

    return reduce($mtimes, 0, 'max');
  }

  /**
   * Creates the response which will be cached by this trait.
   */
  protected abstract function load(Request $request): Response;

  /**
   * Iterates over all files which should expire cache if newer.
   */
  protected abstract function getSourceFiles(Request $request): Iterator;

}
