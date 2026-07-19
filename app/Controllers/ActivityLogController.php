<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ActivityLog;
use App\Middleware\RoleMiddleware;

class ActivityLogController extends Controller {
    private $logModel;

    public function __construct() {
        $this->logModel = new ActivityLog();
    }

    public function index() {
        RoleMiddleware::requirePermission('activity_logs.view');
        
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $logs = $this->logModel->all($limit, $offset);
        $totalLogs = $this->logModel->count();
        $totalPages = ceil($totalLogs / $limit);

        $this->view('activity_logs.index', [
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }
}
