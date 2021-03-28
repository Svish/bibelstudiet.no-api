<?php

namespace Bibelstudiet\Data;

use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

use PHPImage;

use Bibelstudiet\Xml;
use Bibelstudiet\Regex;

/**
 * A quarter.
 */
class QuarterData extends DirectoryData {

  /**
   * @return Iterator Yields data about quarter.
   */
  protected function gatherData(SplFileInfo $quarterDir): Iterator {
    [, $year, $quarter] = $this->parsePath($quarterDir);

    yield 'type' => 'quarter';
    yield 'url' => "$year/$quarter";
    yield 'name' => "$quarter. Kvartal";

    foreach ($this->getQuarterFiles($quarterDir) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);
          yield 'title' => $xml->string('/quarter/title');
          break;
        }

        case 'png': {
          $image = new PHPImage($file->getPathname());
          yield 'image' => [
            'url' => cleanPath($file),
            'size' => $file->getSize(),
            'height' => $image->getHeight(),
            'width' => $image->getWidth(),
          ];
          break;
        }
      }
  }

  protected final function parsePath(SplFileInfo $quarterDir): array {
    return Regex::pathMatch($quarterDir,
      '%(?<year>\d+)/(?<quarter>\d+)$%'
    );
  }

  /**
   * @return Iterator /<year>/<quarter>/*.*
   */
  public static final function getQuarterFiles(SplFileInfo $quarterDir): Iterator {
    $it = new FilesystemIterator($quarterDir);
    $it = new CallbackFilterIterator($it, function(SplFileInfo $file) {
      return $file->isFile();
    });
    return $it;
  }
}
