<?php

namespace App\DTO;

final readonly class ActivityDTO
{
    public function __construct(
        public int $userSessionId,
        public string $action,
        public string $page
    ) {
    }

    public function toArray(): array
    {
        return [
            'user_session_id' => $this->userSessionId,
            'action' => $this->action,
            'page' => $this->page,
        ];
    }
}
