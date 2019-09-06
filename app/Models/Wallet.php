<?php
declare(strict_types=1);

namespace App\Models;

use App\Interfaces\VirtualValetInterface;

/**
 * Class Wallet
 *
 * @package App\Models
 */
class Wallet extends BaseModel implements VirtualValetInterface
{
    /**
     * Amount of Money
     *
     * @var int
     */
    private $amount;

    /**
     * Wallet constructor.
     *
     * @param int $amount
     */
    public function __construct($amount = 0)
    {
        parent::__construct();
        $this->amount = $amount;
    }

    /**
     * Get wallet amount
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
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
        $this->amount += $amount;

        return $this->amount;
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
        return $this->amount -= $amount;
    }
}