<?php

namespace Bibelstudiet\Cache;

interface CacheParameters {

  /**
   * @return string[] List of GET parameters to include in cache key.
   */
  function getCacheParameters(): array;

}
