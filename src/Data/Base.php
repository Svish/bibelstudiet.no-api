<?php

abstract class Data_Base extends Data {

  public static function from(SplFileInfo $dir): self {
    return new static($dir);
  }

  public function __construct(SplFileInfo $dir) {
    $data = $this->gatherData($dir);
    $data = iterator_to_array($data, true);
    parent::__construct($data);
  }

  /**
   * @return Generator Yields data.
   */
  abstract protected function gatherData(SplFileInfo $dir): Generator;
}
