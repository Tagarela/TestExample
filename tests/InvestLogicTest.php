<?php

/**
 * Class InvestLogicTest
 */
class InvestLogicTest extends TestCase
{
    /**
     * generate Loan
     *
     * @return \App\Models\Loan
     */
    private function generateLoan(): \App\Models\Loan
    {
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));

        $loan = new \App\Models\Loan($startDate, $endDate);

        return $loan;
    }

    /**
     * Test invest Tranche A
     */
    public function testInvestTrancheA()
    {
        /*** mock current date ***/
        $knownDate = \Carbon\Carbon::create(2015, 10, 3);
        \Carbon\Carbon::setTestNow($knownDate);

        /*** create test data ***/
        $loan = $this->generateLoan();
        $tranche = \App\Managers\InvestorManager::createTranche($loan, 1000, 3);
        $wallet = new \App\Models\Wallet(1000);
        $investor_1 = new \App\Models\Investor($wallet);
        $transaction = \App\Managers\InvestorManager::createTransaction($tranche, 1000);

        /*** check test data ***/
        $this->assertEmpty($investor_1->getTransactions());
        $this->assertEquals(0, $tranche->calculateAmountOfTransactions());

        \App\Managers\InvestorManager::invest($investor_1, $tranche, $transaction);

        /*** check that data was changed ***/
        $this->assertNotEmpty($investor_1->getTransactions());
        $this->assertNotEquals(0, $tranche->calculateAmountOfTransactions());

        $profitMonthDate = \Carbon\Carbon::createFromFormat('d/m/Y', '31/10/2015', new DateTimeZone('UTC'));
        $amount = \App\Managers\InvestorManager::calculateInvestMonthlyProfitByDate($investor_1, $profitMonthDate);

        $this->assertEquals(28.06, $amount);

          /*** test second investor ***/
        /*** mock current date ***/
        $knownDate = \Carbon\Carbon::create(2015, 10, 4);
        \Carbon\Carbon::setTestNow($knownDate);
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Max tranche amount error');
        $wallet_2 = new \App\Models\Wallet(1000);
        $investor_2 = new \App\Models\Investor($wallet_2);
        $transaction2 = \App\Managers\InvestorManager::createTransaction($tranche, 1);
        \App\Managers\InvestorManager::invest($investor_2, $tranche, $transaction2);
    }

    /**
     * Test invest TrancheB
     */
    public function testInvestTrancheB()
    {
        /*** mock current date ***/
        $knownDate = \Carbon\Carbon::create(2015, 10, 10);
        \Carbon\Carbon::setTestNow($knownDate);

        /*** create test data ***/
        $loan = $this->generateLoan();
        $tranche = \App\Managers\InvestorManager::createTranche($loan, 1000, 6);
        $wallet = new \App\Models\Wallet(1000);
        $investor_3 = new \App\Models\Investor($wallet);
        $transaction = \App\Managers\InvestorManager::createTransaction($tranche, 500);

        /*** check test data ***/
        $this->assertEmpty($investor_3->getTransactions());
        $this->assertEquals(0, $tranche->calculateAmountOfTransactions());

        \App\Managers\InvestorManager::invest($investor_3, $tranche, $transaction);

        /*** check that data was changed ***/
        $this->assertNotEmpty($investor_3->getTransactions());
        $this->assertNotEquals(0, $tranche->calculateAmountOfTransactions());

        $profitMonthDate = \Carbon\Carbon::createFromFormat('d/m/Y', '10/10/2015', new DateTimeZone('UTC'));
        $amount = \App\Managers\InvestorManager::calculateInvestMonthlyProfitByDate($investor_3, $profitMonthDate);

        $this->assertEquals(21.29, $amount);
    }

    /**
     * Test different invest error
     */
    public function testInvestErrors()
    {
        /*** mock current date ***/
        $knownDate = \Carbon\Carbon::create(2015, 10, 10);
        \Carbon\Carbon::setTestNow($knownDate);

        /*** create test data ***/
        $loan = $this->generateLoan();
        $tranche = \App\Managers\InvestorManager::createTranche($loan, 1000, 6);
        $wallet = new \App\Models\Wallet(0);
        $investor_4 = new \App\Models\Investor($wallet);

        /*** test max tranche exception ***/
        try {
            \App\Managers\InvestorManager::createTransaction($tranche, 1100);
        }catch (ErrorException $e){
            $this->assertEquals('Max tranche amount error', $e->getMessage());
        }

        try {
            $transaction = \App\Managers\InvestorManager::createTransaction($tranche, 1000);
            \App\Managers\InvestorManager::invest($investor_4, $tranche, $transaction);
        } catch (ErrorException $e){
            $this->assertEquals('Not enough money', $e->getMessage());
        }
    }
}