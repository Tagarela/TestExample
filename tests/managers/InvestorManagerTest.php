<?php

/**
 * Class InvestorManagerTest
 */
class InvestorManagerTest extends TestCase
{
    /**
     * Test get transaction days logic
     */
    public function testGetCountOfMonthlyTransactionDaysByDate()
    {
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));
        $loan = new \App\Models\Loan($startDate, $endDate);
        $tranche = \App\Managers\InvestorManager::createTranche($loan, 1000, 3);

        $this->assertTrue($tranche instanceof \App\Models\Tranche);

        $transaction = \App\Managers\InvestorManager::createTransaction($tranche, 1000);

        $this->assertTrue($transaction instanceof \App\Models\Transaction);

        $testDate = \Carbon\Carbon::createFromFormat('d/m/Y', '31/10/2015', new DateTimeZone('UTC'));

        /*** test empty transaction ***/
        try {
            \App\Managers\InvestorManager::getCountOfMonthlyTransactionDaysByDate($transaction, $testDate);
        } catch (ErrorException $e){
            $this->assertEquals('transaction wasn\'t paid', $e->getMessage());
        }

        /*** test transaction was created after test date ***/
        $transaction->setPayDate(\App\Helpers\DateHelper::getCurrentDateTime());
        $transaction->setStatus(\App\Models\Transaction::STATUS_SUCCESS);

        try {
            \App\Managers\InvestorManager::getCountOfMonthlyTransactionDaysByDate($transaction, $testDate);
        } catch (ErrorException $e){
            $this->assertEquals('transaction doesn\'t exit for this date', $e->getMessage());
        }

        /*** test transaction was created at the same day ***/
        $transaction->setPayDate($testDate);
        $countDays = \App\Managers\InvestorManager::getCountOfMonthlyTransactionDaysByDate($transaction, $testDate);
        $this->assertEquals(1, $countDays);

        /*** test transaction was created at the beginning of the month ***/
        $transaction->setPayDate(\Carbon\Carbon::createFromFormat('d/m/Y', '03/10/2015', new DateTimeZone('UTC')));
        $countDays = \App\Managers\InvestorManager::getCountOfMonthlyTransactionDaysByDate($transaction,$testDate);
        $this->assertEquals(29, $countDays);

        /*** test transaction was created at previous month ***/
        $transaction->setPayDate(\Carbon\Carbon::createFromFormat('d/m/Y', '03/10/2015', new DateTimeZone('UTC')));
        $countDays = \App\Managers\InvestorManager::getCountOfMonthlyTransactionDaysByDate(
            $transaction,
            \Carbon\Carbon::createFromFormat('d/m/Y', '30/11/2015', new DateTimeZone('UTC'))
        );
        $this->assertEquals(15, $countDays);
    }


    /**
     * Test invest logic
     */
    public function testInvest()
    {
        /*** create test data ***/
        $knownDate = \Carbon\Carbon::create(2015, 10, 10);
        \Carbon\Carbon::setTestNow($knownDate);
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));
        $loan = new \App\Models\Loan($startDate, $endDate);
        $tranche = new \App\Models\Tranche(1000, 3);
        $tranche->setLoan($loan);
        $wallet = new \App\Models\Wallet(1000);
        $investor = new \App\Models\Investor($wallet);

        $this->assertEmpty($investor->getTransactions());
        $this->assertEquals(1000, $investor->getAmount());
        $this->assertEquals(0, $tranche->calculateAmountOfTransactions());

        $transaction_1 = \App\Managers\InvestorManager::createTransaction($tranche, 100);
        $transaction_2 = \App\Managers\InvestorManager::createTransaction($tranche, 400);
        $this->assertEquals(\App\Models\Transaction::STATUS_CREATED, $transaction_1->getStatus());
        $this->assertNull($transaction_1->getPayDate());
        $this->assertEquals(\App\Models\Transaction::STATUS_CREATED, $transaction_2->getStatus());
        $this->assertNull($transaction_2->getPayDate());

        \App\Managers\InvestorManager::invest($investor, $tranche, $transaction_1);

        /*** check amount changes ***/
        $this->assertCount(1, $investor->getTransactions());
        $this->assertEquals(900, $investor->getAmount());
        $this->assertEquals(100, $tranche->calculateAmountOfTransactions());
        $this->assertEquals(\App\Models\Transaction::STATUS_SUCCESS, $transaction_1->getStatus());
        $this->assertNotNull($transaction_1->getPayDate());


        \App\Managers\InvestorManager::invest($investor, $tranche, $transaction_2);

        /*** check amount changes ***/
        $this->assertCount(2, $investor->getTransactions());
        $this->assertEquals(500, $investor->getAmount());
        $this->assertEquals(500, $tranche->calculateAmountOfTransactions());
        $this->assertEquals(\App\Models\Transaction::STATUS_SUCCESS, $transaction_2->getStatus());
        $this->assertNotNull($transaction_2->getPayDate());
    }
}