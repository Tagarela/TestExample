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
     * @var Load
     */
    private $load;

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
     * Set Load
     *
     * @param Load $load
     */
    public function setLoad(Load $load): void
    {
        $this->load = $load;
    }

    /**
     * Get Load
     *
     * @return Load
     */
    public function getLoad(): Load
    {
        return $this->load;
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
        $currentAmount = $this->calculateAmountOfTransaction();
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
    public function calculateAmountOfTransaction(): int
    {
        $amount = 0;
        foreach ($this->transactions as $transaction) {
            $amount += $transaction->amount;
        }
        return $amount;
    }
}