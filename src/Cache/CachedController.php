<?php

namespace Bibelstudiet\Cache;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\Response;
use Bibelstudiet\Controller\Controller;
use Bibelstudiet\Http;

abstract class CachedController extends Controller {

  /**
   * List directories and files current request depends upon.
   */
  protected abstract function getDataSources(): Iterator;

  /**
   * Create response for current request.
   */
  protected abstract function getResponse(): Response;


  public final function get(): void {
    // Create cache key
    $key = $this->request->getPath();

    if ($this instanceof CacheParameters) {
      $get = array_whitelist($_GET, $this->getCacheParameters());
      $get = json_encode($get, JSON_NUMERIC_CHECK);
      $key .= $get;
    }

    // Get cache key
    $cache = Cache::init(get_called_class())->key($key);
    $cached = null;

    // Try get data from cache
    try {
      $cached = $cache->get();

      // Check if cache is still valid
      if($this->getMTime() < $cache->getMTime()) {
        // Get if-headers
        $lmod = @$_SERVER['HTTP_IF_MODIFIED_SINCE'] ?: false;
        $etag = @trim($_SERVER['HTTP_IF_NONE_MATCH']) ?: false;

        // Respond empty 304 if both match
        if($cached['lmod'] == $lmod && $cached['etag'] == rtrim($etag, '-gzip')) {
          header('X-Cache-Hit: perfect hit');
          Http::set_status(304);
        }
        // Otherwise respond with cached
        else {
          header('X-Cache-Hit: hit');
          array_walk($cached['headers'], 'header');
          echo $cached['output'];
        }

        return;
      }
    } catch (\Throwable $e) {
      // Ignore and move on to fresh response instead
    }

    // Get fresh response
    $response = $this->getResponse();

    // Capture it
    ob_start();
    $response->flush();
    $output = ob_get_clean();

    // Cache it
    $lmod = gmdate('D, d M Y H:i:s T', $this->getMTime());
    $etag = '"'.sha1($output).'"';
    $max_age = 10; // TODO: Increase this

    header("Last-Modified: $lmod");
    header("Etag: $etag");
    header("Cache-Control: max-age=$max_age, public");

    $cache->set([
      'lmod' => $lmod,
			'etag' => $etag,
      'headers' => headers_list(),
      'output' => $output,
      // ? Not used, but here for tracking included files during unserialization
      'response' => $response,
    ]);

    // Output it
    header('X-Cache-Hit: '.($cached ? 'expired' : 'miss'));
    echo $output;
  }

  private final function getMTime(iterable $files = null) {
    if ($files === null)
      return max(
        $this->getMTime(iterate_included_files()),
        $this->getMTime($this->getDataSources())
      );

    $mtimes = map($files, function(SplFileInfo $file) {
      return $file->getMTime();
    });

    return reduce($mtimes, 0, 'max');
  }
}
