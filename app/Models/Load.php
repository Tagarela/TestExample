<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\DateHelper;

/**
 * Class Load
 *
 * @package App\Models
 */
class Load extends BaseModel
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
     * Load constructor.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     */
    public function __construct(\DateTime $startDate, \DateTime $endDate)
    {
        parent::__construct();
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
     * Is Load still open
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