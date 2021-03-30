<?php

namespace Bibelstudiet\Data;

use Bibelstudiet\Api\ImageResponse;
use Iterator;
use SplFileInfo;
use FilesystemIterator;
use CallbackFilterIterator;

use Bibelstudiet\Image;
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
    yield 'id' => [$year, $quarter];

    foreach ($this->getQuarterFiles($quarterDir) as $file)
      switch($file->getExtension()) {
        case 'xml': {
          $xml = new Xml($file);
          yield 'title' => $xml->string('/quarter/title');
          break;
        }

        case 'png': {
          $image = new Image($file);
          yield 'image' => [
            'url' => WEBROOT."$year/$quarter.png",
            'width' => $image->getWidth(),
            'height' => $image->getHeight(),
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
