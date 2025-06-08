<?php

namespace App\DTO;

final readonly class PaginationRequestDTO
{
    public function __construct(
        public int $page = 1,
        public int $limit = 20,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?int $userId = null,
        public ?string $action = null
    ) {
    }

    public static function fromRequest(array $requestData): self
    {
        return new self(
            page: max(1, (int)($requestData['page'] ?? 1)),
            limit: (int)($requestData['limit'] ?? 20),
            dateFrom: $requestData['date_from'] ?? null,
            dateTo: $requestData['date_to'] ?? null,
            userId: !empty($requestData['user_id']) ? (int)$requestData['user_id'] : null,
            action: $requestData['action'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'user_id' => $this->userId,
            'action' => $this->action,
            'limit' => $this->limit,
        ];
    }
}
