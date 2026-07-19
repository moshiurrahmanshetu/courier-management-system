<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class UserController extends Controller {
    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index() {
        RoleMiddleware::requirePermission('users.view');
        // Simple user list (for Phase 2 foundation)
        $stmt = \Core\Database::getInstance()->getConnection()->query("SELECT * FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();
        $this->view('users.index', ['users' => $users]);
    }

    public function editRole() {
        RoleMiddleware::requirePermission('users.edit');
        $id = $_GET['id'] ?? null;
        $user = $this->userModel->findById($id);
        if (!$user) $this->redirect('/users');

        $roles = $this->roleModel->all();
        $userRole = $this->userModel->getRole($id);

        $this->view('users.edit_role', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole
        ]);
    }

    public function updateRole() {
        RoleMiddleware::requirePermission('users.edit');
        $userId = $_POST['user_id'] ?? null;
        $roleId = $_POST['role_id'] ?? null;

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/users/edit-role?id=$userId");
        }

        if ($this->userModel->assignRole($userId, $roleId)) {
            Session::flash('success', 'User role updated successfully.');
        } else {
            Session::flash('error', 'Failed to update user role.');
        }
        $this->redirect('/users');
    }
}
