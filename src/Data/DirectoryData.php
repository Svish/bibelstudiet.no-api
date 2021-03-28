<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;

abstract class DirectoryData extends JsonData {

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
