<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    /** @test */
    public function the_next_working_day_christmas_eve()
    {
        $workingDayService = \ReesMcIvor\WorkingDays\WorkingDaysService();
    } 

}