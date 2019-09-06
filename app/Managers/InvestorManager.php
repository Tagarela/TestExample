<?php
declare(strict_types=1);

namespace App\Managers;

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


}