<?php

class ErrorHandler {

  /**
   * @see https://www.php.net/manual/en/function.set-error-handler
   */
  public static function error_handler(int $code, string $error, string $file, int $line): bool {
    // Check if error code is included in error_reporting
    if (error_reporting() & $code)
      throw new ErrorException($error, $code, 0, $file, $line);

    // Don't execute PHP internal error handler
    return true;
  }

  /**
   * @see https://www.php.net/manual/en/function.set-exception-handler
   */
  public static function exception_handler(Throwable $error): void {
    $status = $error instanceof Error_Http
      ? $error->getHttpStatus()
      : 500;
    HTTP::set_status($status);

    $data = ['error' => $error->getMessage()];

    if ($error->getPrevious() != null)
      $data['reason'] = $error->getPrevious()->getMessage();

    if (ENV === 'dev')
      $data += [
        'file' => "{$error->getFile()}@{$error->getLine()}",
        'trace' => $error->getTrace(),
      ];

    (new JsonResponse($data))->flush();
  }

}
