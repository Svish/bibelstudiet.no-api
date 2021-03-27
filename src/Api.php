<?php

final class Api {

  public static function serve(): void {
    $router = new Router(BASE_URI);

    $router->add('/', 'Controller_Index');
    $router->add('/(?<year>\d{4})', 'Controller_Year');
    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)', 'Controller_Quarter');
    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)', 'Controller_Week');
    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)/(?<day>0|1|2|3|4|5|6|7)', 'Controller_Day');

    $router->add('/dates', 'Controller_Datemap');
    $router->add('/validate', 'Controller_Validate');

    // TODO: Limit to configured hosts
    header('Access-Control-Allow-Origin: *');

    $response = $router->run();
    $response->flush();
  }

}
