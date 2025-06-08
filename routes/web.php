<?php

use App\Controller\AuthController;
use App\Controller\CowController;
use App\Controller\DownloadController;
use App\Controller\StatsController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;

return [
    '/' => [
        'controller' => [AuthController::class, 'index'],
        'middleware' => [],
        'methods' => ['GET'],
    ],
    '/login' => [
        'controller' => [AuthController::class, 'login'],
        'middleware' => [],
        'methods' => ['GET', 'POST'],
    ],
    '/logout' => [
        'controller' => [AuthController::class, 'logout'],
        'middleware' => [],
        'methods' => ['GET', 'POST'],
    ],
    '/register' => [
        'controller' => [AuthController::class, 'register'],
        'middleware' => [],
        'methods' => ['GET', 'POST'],
    ],

    '/page-a' => [
        'controller' => [CowController::class, 'index'],
        'middleware' => [AuthMiddleware::class],
        'methods' => ['GET'],
    ],

    '/page-b' => [
        'controller' => [DownloadController::class, 'index'],
        'middleware' => [AuthMiddleware::class],
        'methods' => ['GET'],
    ],

    '/buy-cow' => [
        'controller' => [CowController::class, 'buy'],
        'middleware' => [AuthMiddleware::class],
        'methods' => ['POST'],
    ],

    '/download-file' => [
        'controller' => [DownloadController::class, 'downloadFile'],
        'middleware' => [AuthMiddleware::class],
        'methods' => ['GET'],
    ],

    '/admin/stats' => [
        'controller' => [StatsController::class, 'table'],
        'middleware' => [AdminMiddleware::class],
        'methods' => ['GET'],
    ],

    '/admin/reports' => [
        'controller' => [StatsController::class, 'report'],
        'middleware' => [AdminMiddleware::class],
        'methods' => ['GET'],
    ],
];
