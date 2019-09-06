<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class Tranche
 *
 * @package App\Models
 */
class Transaction extends BaseModel
{
    /*** transaction statuses ***/
    const STATUS_CREATED = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;

    /**
     * Transaction Amount
     *
     * @var
     */
    private $amount;

    /**
     * Status of transaction
     * @var
     */
    private $status;

    /**
     * transaction tranche
     *
     * @var Tranche
     */
    private $tranche;

    /**
     * Transaction Date
     *
     * @var \DateTime
     */
    private $payDate;

    /**
     * Transaction constructor.
     *
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        parent::__construct();
        $this->amount = $amount;
        $this->status = self::STATUS_CREATED;
    }

    /**
     * Get transaction status list
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_CREATED,
            self::STATUS_SUCCESS,
            self::STATUS_FAIL
        ];
    }

    /**
     * Set tranche
     *
     * @param Tranche $tranche
     */
    public function setTranche(Tranche $tranche): void
    {
        $this->tranche = $tranche;
    }

    /**
     * Set status
     *
     * @param int $status
     *
     * @throws \ErrorException
     */
    public function setStatus(int $status)
    {
        $statusList = $this->getStatusList();
        if(!in_array($status, $statusList)){
            throw new \ErrorException('Incorrect status');
        }
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get amount
     *
     * @return mixed
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get transaction tranche
     *
     * @return Tranche|null
     */
    public function getTranche(): ?Tranche
    {
        return $this->tranche;
    }

    /**
     * Get Transaction Pay Date
     *
     * @return \DateTime
     */
    public function getPayDate(): \DateTime
    {
        return $this->payDate;
    }
}