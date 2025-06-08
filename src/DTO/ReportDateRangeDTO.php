<?php

namespace App\DTO;

use App\Core\Request;

readonly class ReportDateRangeDTO
{
    public string $from;

    public string $to;

    public function __construct(Request $request)
    {
        $this->from = $request->get('date_from') ?? date('Y-m-d', strtotime('-30 days'));
        $this->to = $request->get('date_to') ?? date('Y-m-d');

        $fromDate = \DateTime::createFromFormat('Y-m-d', $this->from);
        $toDate = \DateTime::createFromFormat('Y-m-d', $this->to);

        if (!$fromDate || !$toDate) {
            throw new \InvalidArgumentException('Invalid date format. Use Y-m-d.');
        }

        if ($fromDate > $toDate) {
            throw new \InvalidArgumentException('Start date cannot be greater than end date.');
        }

        if ($fromDate->diff($toDate)->days > 62) {
            throw new \InvalidArgumentException('Date range cannot exceed 62 days.');
        }
    }
}
