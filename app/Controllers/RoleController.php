<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class RoleController extends Controller {
    private $roleModel;
    private $permissionModel;

    public function __construct() {
        $this->roleModel = new Role();
        $this->permissionModel = new Permission();
    }

    public function index() {
        RoleMiddleware::requirePermission('roles.view');
        $roles = $this->roleModel->all();
        $this->view('roles.index', ['roles' => $roles]);
    }

    public function create() {
        RoleMiddleware::requirePermission('roles.create');
        $this->view('roles.create');
    }

    public function store() {
        RoleMiddleware::requirePermission('roles.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/roles/create');
        }

        $data = [
            'role_name' => htmlspecialchars($_POST['role_name']),
            'slug' => htmlspecialchars($_POST['slug']),
            'description' => htmlspecialchars($_POST['description']),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($this->roleModel->create($data)) {
            Session::flash('success', 'Role created successfully.');
            $this->redirect('/roles');
        } else {
            Session::flash('error', 'Failed to create role.');
            $this->redirect('/roles/create');
        }
    }

    public function edit() {
        RoleMiddleware::requirePermission('roles.edit');
        $id = $_GET['id'] ?? null;
        $role = $this->roleModel->find($id);
        if (!$role) $this->redirect('/roles');
        $this->view('roles.edit', ['role' => $role]);
    }

    public function update() {
        RoleMiddleware::requirePermission('roles.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/roles/edit?id=$id");
        }

        $data = [
            'role_name' => htmlspecialchars($_POST['role_name']),
            'slug' => htmlspecialchars($_POST['slug']),
            'description' => htmlspecialchars($_POST['description']),
            'status' => $_POST['status'] ?? 'active'
        ];

        if ($this->roleModel->update($id, $data)) {
            Session::flash('success', 'Role updated successfully.');
            $this->redirect('/roles');
        } else {
            Session::flash('error', 'Failed to update role.');
            $this->redirect("/roles/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('roles.delete');
        $id = $_POST['id'] ?? null;
        if ($this->roleModel->delete($id)) {
            Session::flash('success', 'Role deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete role.');
        }
        $this->redirect('/roles');
    }

    public function permissions() {
        RoleMiddleware::requirePermission('roles.edit');
        $id = $_GET['id'] ?? null;
        $role = $this->roleModel->find($id);
        if (!$role) $this->redirect('/roles');

        $groupedPermissions = $this->permissionModel->getGrouped();
        $rolePermissions = array_column($this->roleModel->getPermissions($id), 'id');

        $this->view('roles.permissions', [
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function updatePermissions() {
        RoleMiddleware::requirePermission('roles.edit');
        $roleId = $_POST['role_id'] ?? null;
        $permissionIds = $_POST['permissions'] ?? [];

        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/roles/permissions?id=$roleId");
        }

        if ($this->roleModel->syncPermissions($roleId, $permissionIds)) {
            Session::flash('success', 'Permissions updated successfully.');
        } else {
            Session::flash('error', 'Failed to update permissions.');
        }
        $this->redirect("/roles/permissions?id=$roleId");
    }
}
