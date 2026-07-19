<?php

use App\Models\ActivityLog;
use App\Helpers\Session;

if (!function_exists('logActivity')) {
    function logActivity($action, $description) {
        $logger = new ActivityLog();
        $userId = Session::get('user_id');
        return $logger->log($userId, $action, $description);
    }
}
