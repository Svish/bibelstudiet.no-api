<?php

namespace Bibelstudiet;

use SplFileInfo;
use JsonSerializable;

use Bibelstudiet\Error\DeveloperError;

class Image implements JsonSerializable {
  protected SplFileInfo $file;
  private int $width;
  private int $height;

  private $image;

  private static $output = [
    'ext' => 'png',
    'mime' => 'image/png',
    'fn' => 'imagepng',
    'q' => 9,
  ];

  public function __construct(SplFileInfo $file) {
    $this->file = $file;
    $this->load();
  }

  public function __serialize(): array {
    return [
      'path' => $this->file->getPathname(),
    ];
  }

  public function __unserialize(array $data): void {
    $this->file = new SplFileInfo($data['path']);
    $this->load();
  }

  public function jsonSerialize() {
    return [
      'url' => cleanPath($this->file->getPathInfo()).'.'.static::$output['ext'],
      'width' => $this->getWidth(),
      'height' => $this->getHeight(),
    ];
  }

  public function getWidth(): int {
    return $this->width;
  }

  public function getHeight(): int {
    return $this->height;
  }

  private function load() {
    if (!extension_loaded('gd') && !extension_loaded('gd2'))
      throw new DeveloperError('GD not loaded.');

    [0 => $this->width, 1 => $this->height, 'mime' => $mime] = getimagesize($this->file);

    switch($mime) {
      case 'image/png':
        $this->image = imagecreatefrompng($this->file);
        break;
      case 'image/gif':
        $this->image = imagecreatefromgif($this->file);
        break;
      case 'image/jpeg':
        $this->image = imagecreatefromjpeg($this->file);
        break;
      default:
        throw new DeveloperError("Unsupported image type: {$this->file->getExtension()}");
    }
  }

  public function resize(int $max_width, int $max_height) {
    $width = $this->width;
    $height = $this->height;

    $scale = max(
      $max_width / $this->width,
      $max_height / $this->height
    );

    $new_width = $this->height * $max_width / $max_height;
    $new_height = $this->width * $max_height / $max_width;

    $new = imagecreatetruecolor($max_width, $max_height);
    imagealphablending($new, false);
    imagesavealpha($new, true);
    $transparent = imagecolorallocatealpha($new, 0, 0, 0, 127);
    imagefilledrectangle($new, 0, 0, imagesx($new), imagesy($new), $transparent);

    if ($new_width > $this->width) {
      $h_point = ($this->height - $new_height) / 2;
      imagecopyresampled(
        $new, $this->image,
        0, 0, 0, $h_point,
        $max_width, $max_height, $this->width, $new_height);
    } else {
      $w_point = ($this->width - $new_width) / 2;
      imagecopyresampled(
        $new, $this->image,
        0, 0, $w_point, 0,
        $max_width, $max_height, $new_width, $this->height);
    }

    // header('content-type: text/plain'); var_dump(get_defined_vars()); exit;

    ob_start();
    static::$output['fn']($new, NULL, static::$output['q']);
    $binary = ob_get_clean();

    imagedestroy($new);

    return [static::$output['mime'], $binary];
  }

}
