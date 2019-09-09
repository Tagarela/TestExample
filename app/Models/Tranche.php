<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class Tranche
 *
 * @package App\Models
 */
class Tranche extends BaseModel
{
    /**
     * Maximum amount available to invest
     *
     * @var float
     */
    private $maxAmount;

    /**
     * Monthly interest percentage
     *
     * @var int
     */
    private $percentage;

    /**
     * Transactions
     *
     * @var array
     */
    private $transactions = [];

    /**
     * @var Loan
     */
    private $loan;

    /**
     * Tranche constructor.
     *
     * @param float $maxAmount
     * @param int $percentage
     *
     */
    public function __construct(float $maxAmount, int $percentage)
    {
        parent::__construct();
        $this->maxAmount = $maxAmount;
        $this->percentage = $percentage;
    }

    /**
     * Set max tranche amount
     *
     * @param float $maxAmount
     */
    public function setMaxAmount(float $maxAmount): void
    {
        $this->maxAmount = $maxAmount;
    }

    /**
     * Set tranche percentage
     *
     * @param int $percentage
     */
    public function setPercentage(int $percentage): void
    {
        $this->percentage = $percentage;
    }

    /**
     * Set Loan
     *
     * @param Loan $loan
     */
    public function setLoan(Loan $loan): void
    {
        $this->loan = $loan;
    }

    /**
     * Get Loan
     *
     * @return Loan
     */
    public function getLoan(): Loan
    {
        return $this->loan;
    }

    /**
     * Get percentage
     *
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Get max amount
     *
     * @return float
     */
    public function getMaxAmount()
    {
        return $this->maxAmount;
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @throws \ErrorException
     */
    public function addTransaction(Transaction $transaction): void
    {
        $currentAmount = $this->calculateAmountOfTransactions();
        $transactionAmount = $transaction->getAmount();
        if ($currentAmount + $transactionAmount > $this->maxAmount){
            throw new \ErrorException("Max tranche amount error");
        }
        $this->transactions[] = $transaction;
    }

    /**
     * Calculate amount of all transactions
     *
     * @return int
     */
    public function calculateAmountOfTransactions(): int
    {
        $amount = 0;
        foreach ($this->transactions as $transaction) {
            $amount += $transaction->getAmount();
        }

        return $amount;
    }
}