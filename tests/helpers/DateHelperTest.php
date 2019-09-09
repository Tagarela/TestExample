<?php

/**
 * Class DateHelperTest
 */
class DateHelperTest extends TestCase
{
    /**
     * Test GetNumberOfMonthDaysLogic
     */
    public function testGetNumberOfMonthDaysLogic()
    {
        /*** test date ***/
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $days = \App\Helpers\DateHelper::getNumberOfMonthDays($date);
        $this->assertEquals(31, $days);

        /*** test middle date***/
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', '10/10/2015', new DateTimeZone('UTC'));
        $days = \App\Helpers\DateHelper::getNumberOfMonthDays($date);
        $this->assertEquals(31, $days);

        /*** test another month ***/
        $date = \Carbon\Carbon::createFromFormat('d/m/Y', '11/11/2015', new DateTimeZone('UTC'));
        $days = \App\Helpers\DateHelper::getNumberOfMonthDays($date);
        $this->assertEquals(30, $days);
    }
}