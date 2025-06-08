<?php

namespace App\Service;

use App\DTO\PaginatedResultDTO;
use App\DTO\PaginationRequestDTO;
use App\Enum\ActivityAction;
use App\Repository\ActivityRepository;

class ActivityStatisticsService
{
    public function __construct(
        private readonly ActivityRepository $activityRepository
    ) {
    }

    public function getPaginatedActivities(PaginationRequestDTO $paginationRequest): PaginatedResultDTO
    {
        $totalRecords = $this->activityRepository->countByFilters($paginationRequest);
        $activities = $this->activityRepository->findByFilters($paginationRequest);

        return new PaginatedResultDTO(
            data: $activities,
            totalRecords: $totalRecords,
            currentPage: $paginationRequest->page,
            limit: $paginationRequest->limit
        );
    }

    public function generateReport(array $dateRange): array
    {
        $dateFrom = $dateRange['from'];
        $dateTo = $dateRange['to'];

        return [
            'total_users' => $this->activityRepository->getUniqueUsersCount($dateFrom, $dateTo),
            'total_activities' => $this->activityRepository->getTotalActivitiesCount($dateFrom, $dateTo),
            'cow_purchases' => $this->activityRepository->getActionCount(ActivityAction::CLICK_BUY_COW, $dateFrom, $dateTo),
            'downloads' => $this->activityRepository->getActionCount(ActivityAction::CLICK_DOWNLOAD, $dateFrom, $dateTo),
            'daily_activities' => $this->activityRepository->getDailyActivities($dateFrom, $dateTo),
            'recent_activities' => $this->activityRepository->getRecentActivities($dateFrom, $dateTo, 10),
            'activity_trends' => $this->activityRepository->getActivityTrendsByDate($dateFrom, $dateTo),
            'action_stats' => $this->activityRepository->getActionStats($dateFrom, $dateTo),
        ];
    }
}
