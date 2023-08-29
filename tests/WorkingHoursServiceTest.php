<?php

declare(strict_types=1);

use ReesMcIvor\WorkingDays\WorkingDaysService;

final class WorkingHoursServiceTest extends \Tests\TestCase
{
    /** @test */
    public function a_enquiry_from_midday_is_3_hours_old()
    {
        $workingDaysService = new WorkingDaysService();
        $workingDaysService->setStartTimestamp( now()->setDate(2023,8,29)->subDay(7)->setTime(12,0)->timestamp );
        $workingDaysService->setEndTimestamp( now()->setDate(2023,8,29)->setTime(12, 0)->timestamp );
        $workingDaysService->setWorkingDays();

        $workingDays = collect($workingDaysService->getWorkingDays())->map(function($day) {
            return $day->format('Y-m-d');
        })->toArray();
        
        $this->travelTo( now()->setDate(2023,8,29)->setTime(12,0) );
        $this->assertEquals(11, $workingDaysService->calculateAgeInHours( now()->setDate(2023,8,25)->setTime(9,0), $workingDays));
        $this->assertEquals(3, $workingDaysService->calculateAgeInHours( now()->setDate(2023,8,29)->setTime(9,0), $workingDays));
    }

}
