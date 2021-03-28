<?php

return [

  '/' => '\Bibelstudiet\Controller\RootController',
  '/(?<year>\d{4})' => '\Bibelstudiet\Controller\YearController',
  '/(?<year>\d{4})/(?<quarter>1|2|3|4)' => '\Bibelstudiet\Controller\QuarterController',
  '/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)' => '\Bibelstudiet\Controller\WeekController',
  '/(?<year>\d{4})/(?<quarter>1|2|3|4)/(?<week>\d+)/(?<day>0|1|2|3|4|5|6|7)' => '\Bibelstudiet\Controller\DayController',

  '/dates' => '\Bibelstudiet\Controller\DatesController',
  '/index' => '\Bibelstudiet\Controller\IndexController',
];
