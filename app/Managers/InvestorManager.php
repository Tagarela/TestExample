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
        if (!isset($tranche)) {
            throw new \ErrorException('something went wrong');
        }

        $loan = $tranche->getLoan();
        if (!isset($loan)) {
            throw new \ErrorException('something went wrong');
        }

        if (!$loan->isOpen()) {
            throw new \ErrorException("Loan is not available");
        }

        if ($investor->getAmount() < $transaction->getAmount()) {
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

    /**
     * Calculate investor monthly profit by date
     * @param Investor $investor
     * @param \DateTime $date
     * @return float
     */
    public static function calculateInvestMonthlyProfitByDate(Investor $investor, \DateTime $date): float
    {
        $profit = 0;
        $transactions = $investor->getTransactions();

        foreach ($transactions as $transaction) {
            $profit += self::getTransactionProfit($transaction, $date);
        }

        return $profit;
    }

    /**
     * Claculate profit of one transaction
     *
     * @param Transaction $transaction
     * @param \DateTime $date
     *
     * @return float
     */
    public static function getTransactionProfit(Transaction $transaction, \DateTime $date): float
    {
        $transactionPercentageAmount = self::getTransactionPercentageAmount($transaction);
        $transactionDays = self::getCountOfMonthlyTransactionDaysByDate($transaction, $date);
        $countOfMonthDays = DateHelper::getNumberOfMonthDays($date);

        return $transactionProfit = round($transactionPercentageAmount * $transactionDays / $countOfMonthDays, 2);
    }

    /**
     * Convert transaction percentage to amount
     *
     * @param Transaction $transaction
     * @return float
     */
    public static function getTransactionPercentageAmount(Transaction $transaction): float
    {
        return $transaction->getAmount() * $transaction->getTranche()->getPercentage() / 100;
    }

    /**
     * Get the number of transaction days in a month before the date
     *
     * @param Transaction $transaction
     * @param \DateTime $date
     * @return int
     * @throws \ErrorException
     */
    public static function getCountOfMonthlyTransactionDaysByDate(Transaction $transaction, \DateTime $date): int
    {
        $tranche = $transaction->getTranche();
        if (!isset($tranche)) {
            throw new \ErrorException('Tranche doesn\'t exist');
        }
        $loan = $tranche->getLoan();
        if (!isset($loan)) {
            throw new \ErrorException('Loan doesn\'t exist');
        }
        $loanEndDate = $loan->getEndDate();
        $transactionPayDate = $transaction->getPayDate();

        if ($date < $transactionPayDate) {
            throw new \ErrorException('transaction wasn\'t paid');
        }
        /*** check loan valid ***/
        if ($loanEndDate < $date) {
            /*** calculate days before loan session instead full month days ***/
            if (intval($loanEndDate->format('mY')) == intval($date->format('mY'))) {
                $date = $loanEndDate;
            } else {
                /*** loan was closed before new month ***/
                throw new \ErrorException('Loan was closed!');
            }
        }

        /*** get result if transaction was created in the same month and year ***/
        if ($transactionPayDate->format('mY') == $date->format('mY')) {
            return DateHelper::getNumberOfMonthDays($date) - intval($transactionPayDate->format('d')) + 1;
        }

        return intval($date->format('d'));
    }
}