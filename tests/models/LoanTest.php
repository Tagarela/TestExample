<?php

/**
 * Class LoanTest
 */
class LoanTest extends TestCase
{
    /**
     * test Create
     */
    public function testCreate()
    {
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));

        $loan = new \App\Models\Loan($startDate, $endDate);

        $this->assertTrue($loan instanceof \App\Models\Loan);
        $this->assertEquals($loan->getEndDate(), $endDate);
        $this->assertEquals($loan->getStartDate(), $startDate);
    }

    /**
     * test create loan with incorrect dates
     */
    public function testCreateLoanWithIncorrectDates()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Incorrect load start/end time');
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));
        new \App\Models\Loan($endDate, $startDate);
    }
}