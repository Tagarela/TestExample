<?php
$app = require __DIR__ . '/../bootstrap/app.php';

$knownDate = \Carbon\Carbon::create(2015, 10, 10);
\Carbon\Carbon::setTestNow($knownDate);

$startDate = \Carbon\Carbon::createFromFormat('d/m/Y', '01/10/2015', new DateTimeZone('UTC'));
$endDate = \Carbon\Carbon::createFromFormat('d/m/Y', '15/11/2015', new DateTimeZone('UTC'));

$loan = new \App\Models\Loan($startDate, $endDate);

$trancheA = new \App\Models\Tranche(1000, 3);
$trancheB = new \App\Models\Tranche(1000, 6);

$trancheA->setLoan($loan);
$trancheB->setLoan($loan);

$wallet_1 = new \App\Models\Wallet(1000);
$investor_1 = new \App\Models\Investor($wallet_1);

$wallet_2 = new \App\Models\Wallet(1000);
$investor_2 = new \App\Models\Investor($wallet_2);

$wallet_3 = new \App\Models\Wallet(1000);
$investor_3 = new \App\Models\Investor($wallet_3);

$wallet_4 = new \App\Models\Wallet(1000);
$investor_4 = new \App\Models\Investor($wallet_4);

/*********** As “Investor 1” I’d like to invest 1,000 pounds on the tranche “A” on 03/10/2015: “ok”. **********/

/*** set current date ***/
$knownDate = \Carbon\Carbon::create(2015, 10, 3);
\Carbon\Carbon::setTestNow($knownDate);
/*** set current date ***/

$transaction_1 = \App\Managers\InvestorManager::createTransaction($trancheA, 1000);
\App\Managers\InvestorManager::invest($investor_1, $trancheA, $transaction_1);

/*********** As “Investor 2” I’d like to invest 1 pound on the tranche “A” on 04/10/2015: “exception”. **********/

/*** set current date ***/
$knownDate = \Carbon\Carbon::create(2015, 10, 4);
\Carbon\Carbon::setTestNow($knownDate);
/*** set current date ***/
try {
    $transaction_2 = \App\Managers\InvestorManager::createTransaction($trancheA, 1);
    \App\Managers\InvestorManager::invest($investor_2, $trancheA, $transaction_2);
} catch (ErrorException $e) {
    echo "investor2 exception: " . $e->getMessage()."<br>";
}

/*********** As “Investor 3” I’d like to invest 500 pounds on the tranche “B” on 10/10/2015: “ok”. **********/

/*** set current date ***/
$knownDate = \Carbon\Carbon::create(2015, 10, 10);
\Carbon\Carbon::setTestNow($knownDate);
/*** set current date ***/

$transaction_3 = \App\Managers\InvestorManager::createTransaction($trancheB, 500);
\App\Managers\InvestorManager::invest($investor_3, $trancheB, $transaction_3);

/*********** As “Investor 4” I’d like to invest 1,100 pounds on the tranche “B” 25/10/2015: “exception”. **********/

/*** set current date ***/
$knownDate = \Carbon\Carbon::create(2015, 10, 31);
\Carbon\Carbon::setTestNow($knownDate);
/*** set current date ***/
try {
    $transaction_4 = \App\Managers\InvestorManager::createTransaction($trancheB, 1100);
    \App\Managers\InvestorManager::invest($investor_4, $trancheB, $transaction_4);
} catch (ErrorException $e){
    echo "investor4 exception: " . $e->getMessage(). "<br>";
}

/********* Calculate profit *********/
$profit_1 = \App\Managers\InvestorManager::calculateInvestMonthlyProfitByDate($investor_1, $knownDate);
$profit_2 = \App\Managers\InvestorManager::calculateInvestMonthlyProfitByDate($investor_3, $knownDate);

echo "Investor 1 earns {$profit_1} pounds<br>";
echo "Investor 3 earns {$profit_2} pounds";
