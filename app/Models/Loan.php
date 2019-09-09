<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\DateHelper;

/**
 * Class Loan
 *
 * @package App\Models
 */
class Loan extends BaseModel
{
    /**
     * @var int
     */
    private $startDate;

    /**
     * @var int
     */
    private $endDate;

    /**
     * Loan constructor.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @throws \ErrorException
     */
    public function __construct(\DateTime $startDate, \DateTime $endDate)
    {
        parent::__construct();
        if ($startDate > $endDate) {
            throw new \ErrorException('Incorrect load start/end time');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Start Date
     *
     * @return \DateTime|int
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * End Date
     *
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Is Loan still open
     */
    public function isOpen()
    {
        $currentDate = DateHelper::getCurrentDateTime();
        if ($currentDate < $this->startDate || $currentDate > $this->endDate) {
            return false;
        }

        return true;
    }
}