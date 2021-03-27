<?php

class Cache_File {
  private SplFileInfo $file;

  public function __construct(SplFileInfo $file) {
    $this->file = $file;
  }

  public function set($data) {
    $file = $this->file->openFile('c');
    $file->flock(LOCK_EX);
    $file->ftruncate(0);
    $file->fwrite(serialize($data));
    $file->fflush();
    $file->flock(LOCK_UN);
    $file = null;

    return $data;
  }

  public function get(int $last_modified) {
    if ($this->expired($last_modified))
      return null;

    $file = $this->file->openFile('r');
    $file->flock(LOCK_SH);
    $data = $file->fread($file->getSize());
    $file->flock(LOCK_UN);
    $file = null;

    return unserialize($data);
  }

  private function expired(int $last_modified): bool {

    // TODO: Remove
    return true;

    if ( ! $this->file->isFile()) {
      header('X-Cache-Hit: miss');
      return true;
    }

    if ($this->file->getMTime() < $last_modified) {
      header('X-Cache-Hit: expired');
      return true;
    }

    header('X-Cache-Hit: hit');
    return false;
  }
}
