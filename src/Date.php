<?php

namespace Bibelstudiet;

use DateTimeImmutable;
use DateInterval;

class Date extends DateTimeImmutable {

  public function addDays(int $days): self {
    return $this->add(new DateInterval("P{$days}D"));
  }

  public function subDays(int $days): self {
    return $this->sub(new DateInterval("P{$days}D"));
  }

  public function __toString() {
    return $this->format('Y-m-d');
  }
}
