<?php

namespace Bibelstudiet\Controller;

use Iterator;
use SplFileInfo;

use Bibelstudiet\Api\PdfResponse;
use Bibelstudiet\Cache\CachedController;
use Bibelstudiet\Content;

class PdfController extends CachedController {

  final protected function getPdfFile(): SplFileInfo {
    $year = intval($this->request->year);
    $quarter = intval($this->request->quarter);
    return Content::getFile($year, $quarter, "$year-$quarter.pdf");
  }

  protected function getDataSources(): Iterator {
    yield $this->getPdfFile();
  }

  protected function getResponse(): PdfResponse {
    $file = $this->getPdfFile();
    return new PdfResponse($file);
  }

}
