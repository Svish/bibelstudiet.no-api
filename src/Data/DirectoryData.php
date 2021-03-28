<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\JsonResponse;

abstract class DirectoryData extends JsonResponse {

  public function __construct(SplFileInfo $dir) {
    $data = $this->gatherData($dir);
    $data = iterator_to_array($data, true);
    parent::__construct($data);
  }

  /**
   * @return Iterator Yields data.
   */
  abstract protected function gatherData(SplFileInfo $dir): Iterator;
}
