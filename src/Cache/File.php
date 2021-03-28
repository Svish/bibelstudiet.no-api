<?php

namespace Bibelstudiet\Cache;

use SplFileInfo;

class File {
  private SplFileInfo $file;

  public function __construct(SplFileInfo $file) {
    $this->file = $file;
  }

  public function set($data): void {
    $file = $this->file->openFile('c');
    $file->flock(LOCK_EX);
    $file->ftruncate(0);
    $file->fwrite(serialize($data));
    $file->fflush();
    $file->flock(LOCK_UN);
    $file = null;
  }

  public function get() {
    $file = $this->file->openFile('r');
    $file->flock(LOCK_SH);
    $data = $file->fread($file->getSize());
    $file->flock(LOCK_UN);
    $file = null;

    return unserialize($data);
  }

  public function getMTime(): int {
    return $this->file->getMTime();
  }
}
