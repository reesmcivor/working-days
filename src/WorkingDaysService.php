<?php

namespace ReesMcIvor\WorkingDays;

class WorkingDaysService {

    public $startTime = 0;

    public function setStartDate( $startTime )
    {
        $this->startTime = $startTime;
    }

}