<?php

namespace ReesMcIvor\WorkingDays;

use Carbon\Carbon;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

class WorkingDaysService
{

    public array $workingDays = [];
    public string $country = 'england-and-wales';

    public int $startDateTimestamp = 0;
    public int $endDateTimestamp = 0;

    public bool $includeWeekends = false;

    public function setStartTimestamp($startDateTimestamp): self
    {
        $this->startDateTimestamp = $startDateTimestamp;
        return $this;
    }

    public function setEndTimestamp($endDateTimestamp): self
    {
        $this->endDateTimestamp = $endDateTimestamp;
        return $this;
    }

    public function setCountry($country): self
    {
        $this->country = $country;
        return $this;
    }

    public function isWorkingDay(Carbon $date)
    {
        if(!$this->includeWeekends && $date->isWeekend())
            return false;

        if(UkBankHolidayFactory::getByDate($date->year, $date->month, $date->day))
            return false;

        return true;
    }

    public function getNextWorkingDay( $nowAsTimestamp = null )
    {
        $now = Carbon::createFromTimestamp($nowAsTimestamp ?? time() );
        $nextWorkingDay = $now->copy()->addDay();
        do {
            if($this->isWorkingDay($nextWorkingDay)) {
                return $nextWorkingDay;
            }
            $nextWorkingDay->addDay();
        } while(true);
    }

    public function setWorkingDays() : void
    {
        if (!$this->startDateTimestamp)
            throw new \Exception('Start time not set');

        if (!$this->endDateTimestamp)
            throw new \Exception('Finish time not set');

        if (!$this->country)
            throw new \Exception('Country not set');

        $carbonDateRange = $this->getDatePeriod(
            Carbon::createFromTimestamp($this->startDateTimestamp),
            Carbon::createFromTimestamp($this->endDateTimestamp)
        );

        foreach ($carbonDateRange as $date) {
            if($this->isWorkingDay($date)) {
                $this->workingDays[] = $date;
            }
        }
    }

    public function getWorkingDays() : array
    {
        return $this->workingDays;
    }

    private function getDatePeriod($startTime, $finishTime): \Carbon\CarbonPeriod
    {
        return \Carbon\CarbonPeriod::create($startTime, $finishTime);
    }

    function calculateAgeInHours( Carbon $inquiryDate, array $workingDays, $startWorkHour = 9, $endWorkHour = 17, $now = null) {

        if(!$now) {
            $now = now();
        }

        $hours = 0;
        $currentDate = clone $inquiryDate;

        while ($currentDate->lessThan($now)) {
            $formattedCurrentDate = $currentDate->format('Y-m-d');
            if (!$this->includeWeekends && $currentDate->isWeekend()) {
                $currentDate->addHour();
                continue;
            }
            if (in_array($formattedCurrentDate, $workingDays)) {
                if ($currentDate->hour >= $startWorkHour && $currentDate->hour < $endWorkHour) {
                    $hours++;
                }
            }
            $currentDate->addHour();
        }

        return $hours;
    }

}
