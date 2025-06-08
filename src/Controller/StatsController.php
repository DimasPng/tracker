<?php

namespace App\Controller;

use App\Contract\ResponseFactoryInterface;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\DTO\PaginationRequestDTO;
use App\DTO\ReportDateRangeDTO;
use App\Enum\ActivityAction;
use App\Repository\UserRepository;
use App\Service\ActivityStatisticsService;
use App\Util\Logger;

class StatsController
{
    public function __construct(
        private readonly ActivityStatisticsService $activityStatistics,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly UserRepository $userRepository
    ) {
    }

    public function table(Request $request): Response
    {
        try {
            $requestData = [
                'page' => $request->get('page'),
                'limit' => $request->get('limit'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'user_id' => $request->get('user_id'),
                'action' => $request->get('action'),
            ];

            $paginationRequest = PaginationRequestDTO::fromRequest($requestData);

            $result = $this->activityStatistics->getPaginatedActivities($paginationRequest);
            $users = $this->userRepository->getAllForFilter();

            $content = View::render('admin.stats', [
                'activities' => $result->data,
                'filters' => $paginationRequest->toArray(),
                'availableActions' => ActivityAction::values(),
                'users' => $users,
                'pagination' => $result->getPaginationData(),
            ]);

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Stats table error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Statistics temporarily unavailable', 500);
        }
    }

    public function report(Request $request): Response
    {
        try {
            $range = new ReportDateRangeDTO($request);

            $reportData = $this->activityStatistics->generateReport(['from' => $range->from, 'to' => $range->to]);

            $users = $this->userRepository->getAllForFilter();

            $content = View::render('admin.reports', [
                'reportData' => $reportData,
                'users' => $users,
                'dateFrom' => $range->from,
                'dateTo' => $range->to,
            ]);

            return $this->responseFactory->html($content);
        } catch (\InvalidArgumentException $e) {
            return $this->responseFactory->error($e->getMessage());
        } catch (\Throwable $e) {
            Logger::error('Report error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Reports temporarily unavailable', 500);
        }
    }
}
