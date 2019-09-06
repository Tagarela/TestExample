<?php
declare(strict_types=1);

namespace App\Managers;

use App\Helpers\DateHelper;
use App\Models\Investor;
use App\Models\Tranche;
use App\Models\Transaction;

/**
 * Class InvestorManager
 *
 * @package App\Managers
 */
class InvestorManager
{
    /**
     * Make an investment
     *
     * @param Investor $investor
     * @param Transaction $transaction
     *
     * @return Investor
     *
     * @throws \ErrorException
     */
    public static function invest(Investor $investor, Transaction $transaction): Investor
    {
        $tranche = $transaction->getTranche();
        if(!isset($tranche)){
            throw new \ErrorException('something went wrong');
        }

        $load = $tranche->getLoad();
        if(!isset($load)){
            throw new \ErrorException('something went wrong');
        }

        if (!$load->isOpen()) {
            throw new \ErrorException("Load is not available");
        }

        if($investor->getAmount() < $transaction->getAmount()) {
            throw new \ErrorException('Not enough money');
        }

        $investor->chargeAmount($transaction->getAmount());

        $transaction->setStatus(Transaction::STATUS_SUCCESS);
        $transaction->setPayDate(DateHelper::getCurrentDateTime());
        $investor->addTransaction($transaction);

        return $investor;
    }

    /**
     * Create Transaction
     *
     * @param Tranche $tranche
     * @param int $amount
     *
     * @return Transaction
     */
    public static function createTransaction(Tranche $tranche, int $amount): Transaction
    {
        $transaction = new Transaction($amount);
        $transaction->setTranche($tranche);
        $transaction->setStatus(Transaction::STATUS_CREATED);

        return $transaction;
    }

    public static function calculateInvestProfit(Investor $investor)
    {
        $profit = 0;
        $transactions = $investor->getTransactions();

        foreach($transactions as $transaction){
            self::calculateTransactionAmount($transaction);
            die();
            $transactionDate = $transaction->getPayDate();
            $transactionAmount = $transaction->getAmount();


            var_dump($transaction);
            die();
        }
    }

    public static function calculateTransactionAmount($transaction)
    {
        $transactionDate = $transaction->getPayDate();
        $transactionAmount = $transaction->getAmount();
        $percentage = $transaction->getTranche()->getPercentage();

        $currentMonthDays = DateHelper::getNumberOfCurrentMonthDays();
        $amountPerDay = ($transactionAmount * $percentage)/100;

        var_dump($amountPerDay);
        die();
        var_dump($transactionDate);
        var_dump( intval($transactionDate->format('d')));


    }
}