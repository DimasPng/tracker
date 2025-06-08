<?php

namespace App\Controller;

use App\Contract\ResponseFactoryInterface;
use App\Core\Response;
use App\Core\View;
use App\Enum\ActivityAction;
use App\Enum\AppRoute;
use App\Service\ActivityLoggerService;
use App\Util\Logger;

class CowController
{
    public function __construct(
        private readonly ActivityLoggerService $activityLogger,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function index(): Response
    {
        try {
            $this->activityLogger->log(ActivityAction::PAGE_VIEW_A, AppRoute::PAGE_A);

            $content = View::render('pages.page-a');

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Page A error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Page temporarily unavailable', 500);
        }
    }

    public function buy(): Response
    {
        try {
            $this->activityLogger->log(ActivityAction::CLICK_BUY_COW, AppRoute::PAGE_A);

            $content = View::render('pages.page-a', ['showThankYou' => true]);

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Buy cow error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Purchase service temporarily unavailable', 500);
        }
    }
}
