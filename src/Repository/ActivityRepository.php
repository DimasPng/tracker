<?php

namespace App\Repository;

use App\DTO\ActivityDTO;
use App\DTO\PaginationRequestDTO;
use App\Enum\ActivityAction;
use PDO;

class ActivityRepository
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function save(ActivityDTO $activity): void
    {
        $data = $activity->toArray();

        $stmt = $this->pdo->prepare(
            'INSERT INTO activities (user_session_id, action, page) 
         VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $data['user_session_id'],
            $data['action'],
            $data['page'],
        ]);
    }

    public function findByFilters(PaginationRequestDTO $paginationRequest): array
    {
        $sql = 'SELECT a.id, a.user_session_id, a.action, a.page, a.created_at, 
                       us.user_id, us.ip_address, us.user_agent, u.email as user_email 
                FROM activities a 
                LEFT JOIN user_sessions us ON a.user_session_id = us.id
                LEFT JOIN users u ON us.user_id = u.id 
                WHERE 1=1';

        $params = [];

        if ($paginationRequest->dateFrom) {
            $sql .= ' AND a.created_at >= ?';
            $params[] = $paginationRequest->dateFrom . ' 00:00:00';
        }

        if ($paginationRequest->dateTo) {
            $sql .= ' AND a.created_at <= ?';
            $params[] = $paginationRequest->dateTo . ' 23:59:59';
        }

        if ($paginationRequest->userId) {
            $sql .= ' AND us.user_id = ?';
            $params[] = $paginationRequest->userId;
        }

        if ($paginationRequest->action) {
            $sql .= ' AND a.action = ?';
            $params[] = $paginationRequest->action;
        }

        $sql .= ' ORDER BY a.created_at DESC';

        $sql .= ' LIMIT ?';
        $params[] = $paginationRequest->limit;

        $sql .= ' OFFSET ?';
        $params[] = ($paginationRequest->page - 1) * $paginationRequest->limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByFilters(PaginationRequestDTO $paginationRequest): int
    {
        $sql = 'SELECT COUNT(*) 
                FROM activities a 
                LEFT JOIN user_sessions us ON a.user_session_id = us.id
                LEFT JOIN users u ON us.user_id = u.id 
                WHERE 1=1';

        $params = [];

        if ($paginationRequest->dateFrom) {
            $sql .= ' AND DATE(a.created_at) >= ?';
            $params[] = $paginationRequest->dateFrom;
        }

        if ($paginationRequest->dateTo) {
            $sql .= ' AND DATE(a.created_at) <= ?';
            $params[] = $paginationRequest->dateTo;
        }

        if ($paginationRequest->userId) {
            $sql .= ' AND us.user_id = ?';
            $params[] = $paginationRequest->userId;
        }

        if ($paginationRequest->action) {
            $sql .= ' AND a.action = ?';
            $params[] = $paginationRequest->action;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }

    public function getActionStats(string $dateFrom, string $dateTo): array
    {
        $stmt = $this->pdo->prepare('
        SELECT 
            a.action,
            COUNT(*) as count,
            COUNT(DISTINCT us.user_id) as unique_users,
            MAX(a.created_at) as last_activity
        FROM activities a
        LEFT JOIN user_sessions us ON a.user_session_id = us.id
        WHERE a.created_at BETWEEN ? AND ?
        GROUP BY a.action
        ORDER BY count DESC
    ');
        $stmt->execute([$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDailyActivities(string $dateFrom, string $dateTo): array
    {
        $stmt = $this->pdo->prepare('
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count
        FROM activities 
        WHERE created_at BETWEEN ? AND ?
        GROUP BY DATE(created_at)
    ');
        $stmt->execute([$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniqueUsersCount(string $dateFrom, string $dateTo): int
    {
        $stmt = $this->pdo->prepare('
        SELECT COUNT(DISTINCT us.user_id) 
        FROM activities a
        LEFT JOIN user_sessions us ON a.user_session_id = us.id
        WHERE a.created_at BETWEEN ? AND ? AND us.user_id IS NOT NULL
    ');
        $stmt->execute([$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        return (int)$stmt->fetchColumn();
    }

    public function getTotalActivitiesCount(string $dateFrom, string $dateTo): int
    {
        $stmt = $this->pdo->prepare('
        SELECT COUNT(*) 
        FROM activities 
        WHERE created_at BETWEEN ? AND ?
    ');
        $stmt->execute([$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        return (int)$stmt->fetchColumn();
    }

    public function getActionCount(ActivityAction $action, string $dateFrom, string $dateTo): int
    {
        $stmt = $this->pdo->prepare('
        SELECT COUNT(*) 
        FROM activities 
        WHERE action = ? AND created_at BETWEEN ? AND ?
    ');
        $stmt->execute([
            $action->value,
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59',
        ]);

        return (int)$stmt->fetchColumn();
    }

    public function getRecentActivities(string $dateFrom, string $dateTo, int $limit): array
    {
        $stmt = $this->pdo->prepare('
        SELECT a.id, a.user_session_id, a.action, a.page, a.created_at, 
               us.user_id, us.ip_address, us.user_agent, u.email as user_email
        FROM activities a 
        LEFT JOIN user_sessions us ON a.user_session_id = us.id
        LEFT JOIN users u ON us.user_id = u.id 
        WHERE a.created_at BETWEEN ? AND ?
        ORDER BY a.created_at DESC
        LIMIT ?
    ');
        $stmt->execute([
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59',
            $limit,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivityTrendsByDate(string $dateFrom, string $dateTo): array
    {
        $pageViewA = ActivityAction::PAGE_VIEW_A->value;
        $pageViewB = ActivityAction::PAGE_VIEW_B->value;
        $clickBuyCow = ActivityAction::CLICK_BUY_COW->value;
        $clickDownload = ActivityAction::CLICK_DOWNLOAD->value;

        $stmt = $this->pdo->prepare('
        SELECT 
            DATE(created_at) as date,
            SUM(CASE WHEN action = ? THEN 1 ELSE 0 END) as page_view_a,
            SUM(CASE WHEN action = ? THEN 1 ELSE 0 END) as page_view_b,
            SUM(CASE WHEN action = ? THEN 1 ELSE 0 END) as click_buy_cow,
            SUM(CASE WHEN action = ? THEN 1 ELSE 0 END) as click_download
        FROM activities 
        WHERE created_at BETWEEN ? AND ?
        GROUP BY DATE(created_at)
    ');

        $stmt->execute([
            $pageViewA,
            $pageViewB,
            $clickBuyCow,
            $clickDownload,
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59',
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
