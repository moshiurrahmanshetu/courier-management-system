<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Permission;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class PermissionController extends Controller {
    private $permissionModel;

    public function __construct() {
        $this->permissionModel = new Permission();
    }

    public function index() {
        RoleMiddleware::requirePermission('permissions.view');
        $permissions = $this->permissionModel->all();
        $this->view('permissions.index', ['permissions' => $permissions]);
    }

    public function create() {
        RoleMiddleware::requirePermission('permissions.create');
        $this->view('permissions.create');
    }

    public function store() {
        RoleMiddleware::requirePermission('permissions.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/permissions/create');
        }

        $data = [
            'permission_name' => htmlspecialchars($_POST['permission_name']),
            'permission_key' => htmlspecialchars($_POST['permission_key']),
            'module' => htmlspecialchars($_POST['module']),
            'description' => htmlspecialchars($_POST['description'])
        ];

        if ($this->permissionModel->create($data)) {
            Session::flash('success', 'Permission created successfully.');
            $this->redirect('/permissions');
        } else {
            Session::flash('error', 'Failed to create permission.');
            $this->redirect('/permissions/create');
        }
    }

    public function edit() {
        RoleMiddleware::requirePermission('permissions.edit');
        $id = $_GET['id'] ?? null;
        $permission = $this->permissionModel->find($id);
        if (!$permission) $this->redirect('/permissions');
        $this->view('permissions.edit', ['permission' => $permission]);
    }

    public function update() {
        RoleMiddleware::requirePermission('permissions.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/permissions/edit?id=$id");
        }

        $data = [
            'permission_name' => htmlspecialchars($_POST['permission_name']),
            'permission_key' => htmlspecialchars($_POST['permission_key']),
            'module' => htmlspecialchars($_POST['module']),
            'description' => htmlspecialchars($_POST['description'])
        ];

        if ($this->permissionModel->update($id, $data)) {
            Session::flash('success', 'Permission updated successfully.');
            $this->redirect('/permissions');
        } else {
            Session::flash('error', 'Failed to update permission.');
            $this->redirect("/permissions/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('permissions.delete');
        $id = $_POST['id'] ?? null;
        if ($this->permissionModel->delete($id)) {
            Session::flash('success', 'Permission deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete permission.');
        }
        $this->redirect('/permissions');
    }
}
