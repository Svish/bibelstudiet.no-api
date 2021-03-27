<?php

/**
 * A quarter.
 */
class Data_Quarter extends Data_Base {

  /**
   * @return Generator Yields data about quarter.
   */
  protected function gatherData(SplFileInfo $quarterDir): Generator {
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
