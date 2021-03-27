<?php

trait Cache_Get {
	protected $parameter_whitelist = [];

  public function get(Request $request): Response {
    if (isset($_GET['sources']))
      return new JsonResponse(mapToArray($this->getSourceFiles($request),
        function(SplFileInfo $file) {
          return cleanPath($file);
        }
      ));

    $files = map($this->getSourceFiles($request),
      function(SplFileInfo $file) {
        return $file->getMTime();
      }
    );

    $last_modified = reduce($files, 0, 'max');

    $get = array_whitelist($_GET, $this->parameter_whitelist);
    $get = json_encode($get, JSON_NUMERIC_CHECK);
    $key = $request->getPath().$get;
    $cache = Cache_Cache::init(get_called_class())->key($key);
    $data = $cache->get($last_modified);

    if ($data !== null)
      return $data;

    $data = $this->load($request);
    $cache->set($data);
    return $data;
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
