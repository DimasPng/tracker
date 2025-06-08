<?php

namespace App\Service;

use App\Contract\UserSessionServiceInterface;
use App\DTO\ActivityDTO;
use App\Enum\ActivityAction;
use App\Enum\AppRoute;
use App\Repository\ActivityRepository;

class ActivityLoggerService
{
    public function __construct(
        private readonly ActivityRepository $activityRepository,
        private readonly UserSessionServiceInterface $userSessionService
    ) {
    }

    public function log(ActivityAction $action, AppRoute $route): void
    {
        $userSession = $this->userSessionService->getCurrentSession();

        $activity = new ActivityDTO(
            userSessionId: $userSession->id,
            action: $action->value,
            page: $route->value
        );

        $this->activityRepository->save($activity);
        $this->userSessionService->updateLastActivity();
    }
}
