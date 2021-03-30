<?php

namespace Bibelstudiet\Api;

use SplFileInfo;

use Bibelstudiet\Image;

class ImageResponse implements Response {
  protected Image $image;
  protected $resized;

  public function __construct(SplFileInfo $file) {
    $this->image = new Image($file);
    // TODO: Get from GET parameters.
    $this->resized = $this->image->resize(700, 995);
  }

  public function flush(): void {
    [$mime, $binary] = $this->resized;

    header("Content-Type: $mime");
    echo $binary;
  }
}
