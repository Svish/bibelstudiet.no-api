<?php

namespace Bibelstudiet\Api;

final class Api {

  public static function serve(): void {
    $router = new Router(BASE_URI);

    $router->add('/', '\Bibelstudiet\Controller\IndexController');

    $router->add('/(?<year>\d{4})', '\Bibelstudiet\Controller\YearController');

    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)', '\Bibelstudiet\Controller\QuarterController');

    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)', '\Bibelstudiet\Controller\WeekController');

    $router->add('/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)/(?<day>0|1|2|3|4|5|6|7)', '\Bibelstudiet\Controller\DayController');


    $router->add('/dates', '\Bibelstudiet\Controller\DatemapController');

    $router->add('/validate', '\Bibelstudiet\Controller\ValidateController');


    // TODO: Limit to configured hosts
    header('Access-Control-Allow-Origin: *');

    $response = $router->run();
    $response->flush();
  }

}
