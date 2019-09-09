<?php
declare(strict_types=1);

namespace App\Models;

use App\Helpers\DateHelper;
use Carbon\Carbon;

/**
 * Class Loan
 *
 * @package App\Models
 */
class Loan extends BaseModel
{
    /**
     * @var Carbon
     */
    private $startDate;

    /**
     * @var Carbon
     */
    private $endDate;

    /**
     * Loan constructor.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @throws \ErrorException
     */
    public function __construct(Carbon $startDate, Carbon $endDate)
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
     * @return Carbon
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