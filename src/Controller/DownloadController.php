<?php

namespace App\Controller;

use App\Contract\ResponseFactoryInterface;
use App\Core\Response;
use App\Core\View;
use App\Enum\ActivityAction;
use App\Enum\AppRoute;
use App\Service\ActivityLoggerService;
use App\Util\Logger;

class DownloadController
{
    public function __construct(
        private readonly ActivityLoggerService $activityLogger,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function index(): Response
    {
        try {
            $this->activityLogger->log(ActivityAction::PAGE_VIEW_B, AppRoute::PAGE_B);

            $content = View::render('pages.page-b');

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Download page error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Download page temporarily unavailable', 500);
        }
    }

    public function downloadFile(): Response
    {
        try {
            $this->activityLogger->log(ActivityAction::CLICK_DOWNLOAD, AppRoute::PAGE_B);

            $filename = 'demo-app.exe';
            $filePath = __DIR__ . '/../../public/downloads/' . $filename;

            return $this->responseFactory->download($filePath, $filename);
        } catch (\Throwable $e) {
            Logger::error('Download file error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('File download temporarily unavailable', 500);
        }
    }
}
