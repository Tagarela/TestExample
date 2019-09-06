<?php
$app = require __DIR__.'/../bootstrap/app.php';

try {
    $wallet = new \App\Models\Wallet();
    $investor = new \App\Models\Investor($wallet);
    $investor->addAmount(1000);

    $trancheA = new \App\Models\Tranche(1000, 3);
    $trancheB = new \App\Models\Tranche(1000, 6);

    $startDate = DateTime::createFromFormat('d/m/Y', '01/09/2019', new DateTimeZone('UTC'));
    $endDate = DateTime::createFromFormat('d/m/Y', '01/10/2019', new DateTimeZone('UTC'));

    $load = new \App\Models\Load($startDate, $endDate);

    $trancheA->setLoad($load);
    $trancheB->setLoad($load);

    $transaction = \App\Managers\InvestorManager::createTransaction($trancheA, 1000);
    $investor = \App\Managers\InvestorManager::invest($investor, $transaction);

    var_dump($investor);die();

} catch (ErrorException $e){
    var_dump($e->getMessage());
}