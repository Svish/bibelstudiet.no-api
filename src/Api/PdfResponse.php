<?php

namespace Bibelstudiet\Api;

use SplFileInfo;

class PdfResponse implements Response {
  protected SplFileInfo $file;

  public function __construct(SplFileInfo $file) {
    $this->file = $file;
  }

  public function __serialize(): array {
    return [
      'path' => $this->file->getPathname(),
    ];
  }

  public function __unserialize(array $data): void {
    $this->file = new SplFileInfo($data['path']);
  }

  public function flush(): void {
    header('Content-Type: application/pdf');

    $file = $this->file->openFile('r');
    $file->fpassthru();
    $file = null;
  }
}
