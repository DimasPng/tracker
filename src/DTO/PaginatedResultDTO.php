<?php

namespace App\DTO;

final readonly class PaginatedResultDTO
{
    public function __construct(
        public array $data,
        public int $totalRecords,
        public int $currentPage,
        public int $limit
    ) {
    }

    public function getPaginationData(): array
    {
        $totalPages = (int) ceil($this->totalRecords / $this->limit);
        $offset = ($this->currentPage - 1) * $this->limit;

        return [
            'current_page' => $this->currentPage,
            'total_pages' => $totalPages,
            'total_records' => $this->totalRecords,
            'limit' => $this->limit,
            'has_previous' => $this->currentPage > 1,
            'has_next' => $this->currentPage < $totalPages,
            'previous_page' => max(1, $this->currentPage - 1),
            'next_page' => min($totalPages, $this->currentPage + 1),
            'start_record' => $this->totalRecords > 0 ? $offset + 1 : 0,
            'end_record' => min($offset + $this->limit, $this->totalRecords),
        ];
    }
}
