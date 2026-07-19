<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Helpers\Session;
use App\Middleware\AuthMiddleware;

class DashboardController extends Controller {
    public function index() {
        AuthMiddleware::handle();
        
        $userModel = new User();
        $user = $userModel->findById(Session::get('user_id'));
        
        $this->view('dashboard.index', ['user' => $user]);
    }
}
