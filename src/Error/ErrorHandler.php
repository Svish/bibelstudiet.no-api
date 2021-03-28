<?php

namespace Bibelstudiet\Error;

use Iterator;
use Throwable;
use ErrorException;

use Bibelstudiet\Api\JsonResponse;
use Bibelstudiet\Http;

final class ErrorHandler {

  /**
   * @see https://www.php.net/manual/en/function.set-error-handler
   */
  public function error_handler(int $code, string $error, string $file, int $line): bool {
    // Check if error code is included in error_reporting
    if (error_reporting() & $code)
      throw new ErrorException($error, $code, 0, $file, $line);

    // Don't execute PHP internal error handler
    return true;
  }

  /**
   * @see https://www.php.net/manual/en/function.set-exception-handler
   */
  public function exception_handler(Throwable $error): void {
    $status = $error instanceof HttpError
      ? $error->getHttpStatus()
      : 500;
    Http::set_status($status);

    $res = new JsonResponse(static::pickDetails($error));
    $res->flush();
  }

  private static function pickDetails(Throwable $error): Iterator {
    yield 'message' => $error->getMessage();
    yield 'type' => get_class($error);

    if ($error->getPrevious() != null)
      yield 'reason' => $error->getPrevious()->getMessage();

    if (ENV === 'dev') {
      yield 'file' => $error->getFile();
      yield 'line' => $error->getLine();
      yield 'trace' => $error->getTraceAsString();
    }
  }

}
