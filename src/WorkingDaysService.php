<?php

namespace ReesMcIvor\WorkingDays;

use Carbon\CarbonPeriod;

class WorkingDaysService {

    public $countries = [];
    public $startTime = 0;
    public $finishTime = 0;

    public function setStartTime( $startTime ) : self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function setFinishTime( $finishTime ) : self
    {
        $this->finishTime = $finishTime;
        return $this;
    }

    public function setCountries( $countries = [] ) : self
    {
        $this->countries = $countries;
        return $this;
    }

    public function getWorkingDays()
    {
        if(!$this->startTime) throw new \Exception('Start time not set');
        if(!$this->finishTime) throw new \Exception('Finish time not set');
        if(!$this->countries) throw new \Exception('Countries not set');

        foreach($this->getDatePeriod($this->startTime, $this->finishTime) as $date) {
            $this->workingDays[] = $date;
        }
        return collect($this->workingDays);
        
    }

    private function getDatePeriod( $startTime, $finishTime ) : CarbonPeriod
    {
        return CarbonPeriod::create($this->startDate, $this->endDate);
    }

    public function getUkHolidays()
    {

    }

}