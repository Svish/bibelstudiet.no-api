<?php

/**
 * A year and its quarters.
 */
class Data_Year extends Data_Base {

  /**
   * @return Generator Yields data about year.
   */
  protected function gatherData(SplFileInfo $yearDir): Generator {
    yield 'type' => 'year';
    yield 'url' => $yearDir->getFilename();
    yield 'name' => $yearDir->getFilename();
  }

}
