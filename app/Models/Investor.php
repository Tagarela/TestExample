<?php
declare(strict_types=1);

namespace App\Models;

use App\Interfaces\VirtualValetInterface;

/**
 * Class Investor
 *
 * @package App\Models
 */
class Investor extends BaseModel
{
    /**
     * @var Wallet
     */
    private $wallet;

    /**
     * @var Transaction[]
     */
    private $transactionList = [];

    /**
     * Investor constructor.
     *
     * @param VirtualValetInterface $wallet
     */
    public function __construct(VirtualValetInterface $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Add amount to the wallet amount
     *
     * @param int $amount
     *
     * @return int
     */
    public function addAmount(int $amount): int
    {
        return $this->wallet->addAmount($amount);
    }

    /**
     * Get investor amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->wallet->getAmount();
    }

    /**
     * Charge amount
     *
     * @param int $amount
     *
     * @return int
     */
    public function chargeAmount(int $amount): int
    {
        return $this->wallet->chargeAmount($amount);
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactionList[] = $transaction;
    }

    /**
     * Get all invest transactions
     *
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactionList;
    }
}