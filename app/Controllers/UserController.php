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
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'role' => $_GET['role'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->searchAndFilter($filters, $limit, $offset);
        $totalUsers = $this->userModel->countAll($filters);
        $totalPages = ceil($totalUsers / $limit);

        $roles = $this->roleModel->all();

        $this->view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('users.create');
        $roles = $this->roleModel->all();
        $this->view('users.create', ['roles' => $roles]);
    }

    public function store() {
        RoleMiddleware::requirePermission('users.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/users/create');
        }

        // Generate temporary password
        $tempPassword = bin2hex(random_bytes(4)); // 8 chars

        $data = [
            'name' => htmlspecialchars($_POST['name']),
            'username' => htmlspecialchars($_POST['username']),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'mobile' => htmlspecialchars($_POST['mobile']),
            'password' => $tempPassword,
            'role_slug' => $_POST['role_slug'],
            'gender' => $_POST['gender'],
            'dob' => $_POST['dob'],
            'address' => htmlspecialchars($_POST['address']),
            'status' => $_POST['status'] ?? 'active',
            'password_change_required' => 1
        ];

        if ($this->userModel->findByEmail($data['email'])) {
            Session::flash('error', 'Email already exists.');
            $this->redirect('/users/create');
        }

        $userId = $this->userModel->create($data);
        if ($userId) {
            logActivity('User Created', "Admin created user: {$data['username']}");
            Session::flash('success', "User created successfully. Temporary password: $tempPassword");
            $this->redirect('/users');
        } else {
            Session::flash('error', 'Failed to create user.');
            $this->redirect('/users/create');
        }
    }

    public function show() {
        RoleMiddleware::requirePermission('users.view');
        $id = $_GET['id'] ?? null;
        $user = $this->userModel->findById($id);
        if (!$user) $this->redirect('/users');
        
        $role = $this->userModel->getRole($id);
        $permissions = $this->userModel->getPermissions($id);

        $this->view('users.show', [
            'user' => $user,
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function edit() {
        RoleMiddleware::requirePermission('users.edit');
        $id = $_GET['id'] ?? null;
        $user = $this->userModel->findById($id);
        if (!$user) $this->redirect('/users');
        
        $roles = $this->roleModel->all();
        $userRole = $this->userModel->getRole($id);

        $this->view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole
        ]);
    }

    public function update() {
        RoleMiddleware::requirePermission('users.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/users/edit?id=$id");
        }

        $data = [
            'name' => htmlspecialchars($_POST['name']),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'mobile' => htmlspecialchars($_POST['mobile']),
            'gender' => $_POST['gender'],
            'dob' => $_POST['dob'],
            'address' => htmlspecialchars($_POST['address']),
            'status' => $_POST['status']
        ];

        // Role update logic
        $newRoleId = $_POST['role_id'] ?? null;
        $currentRole = $this->userModel->getRole($id);
        
        // Prevention: Admin cannot remove own Super Admin access
        if ($id == Session::get('user_id') && $currentRole['slug'] === 'super-admin') {
            $stmtSuper = \Core\Database::getInstance()->getConnection()->prepare("SELECT slug FROM roles WHERE id = ?");
            $stmtSuper->execute([$newRoleId]);
            $newRole = $stmtSuper->fetch();
            if ($newRole['slug'] !== 'super-admin') {
                Session::flash('error', 'You cannot remove your own Super Admin access.');
                $this->redirect("/users/edit?id=$id");
            }
        }

        if ($this->userModel->update($id, $data)) {
            if ($newRoleId) {
                $this->userModel->assignRole($id, $newRoleId);
            }
            logActivity('User Updated', "Admin updated user ID: $id");
            Session::flash('success', 'User updated successfully.');
            $this->redirect('/users');
        } else {
            Session::flash('error', 'Failed to update user.');
            $this->redirect("/users/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('users.delete');
        $id = $_POST['id'] ?? null;
        
        if ($id == Session::get('user_id')) {
            Session::flash('error', 'You cannot delete yourself.');
            $this->redirect('/users');
        }

        if ($this->userModel->softDelete($id)) {
            logActivity('User Deleted', "Admin soft-deleted user ID: $id");
            Session::flash('success', 'User deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete user.');
        }
        $this->redirect('/users');
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
