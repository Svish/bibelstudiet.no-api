<?php

namespace Bibelstudiet;

final class Http {

  /**
   * Set an HTTP status code.
   *
   * @param code HTTP code to use
   */
  public static function set_status(int $statusCode) {
    http_response_code($statusCode);
  }

  /**
   * Get status message for given code.
   */
  public static function code(int $statusCode): string {
    return array_key_exists($statusCode, self::$statusCodes)
      ? self::$statusCodes[$statusCode]
      : 'Unknown';
  }

  /**
   * Redirect to given target.
   *
   * @param code HTTP statis code to use
   * @param target URL to redirect to
   * @param prepend If target should be appended to WEBROOT
   */
  public static function redirect(string $target = null, int $statusCode = 302, bool $append = TRUE): void {
    if ($append)
      $target = WEBROOT.$target;

    header('Location: '.$target, true, $statusCode);
    exit;
  }

  private static $statusCodes = [
    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => 'Switch Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot',
    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',
    425 => 'Unordered Collection',
    426 => 'Upgrade Required',
    449 => 'Retry With',
    450 => 'Blocked by Windows Parental Controls',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',
    507 => 'Insufficient Storage',
    509 => 'Bandwidth Limit Exceeded',
    510 => 'Not Extended'
  ];
}
