<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\ImageResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;

class ImageController extends CachedController {

  final protected function getImageFile(): SplFileInfo {
    $year = intval($this->request->year);
    $quarter = intval($this->request->quarter);
    return Content::getFile($year, $quarter, "$year-$quarter.png");
  }

  protected function getDataSources(): Iterator {
    yield $this->getImageFile();
  }

  protected function getResponse(): ImageResponse {
    $file = $this->getImageFile();
    return new ImageResponse($file);
  }

}
