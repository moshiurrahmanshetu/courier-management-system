<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Branch;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class BranchController extends Controller {
    private $branchModel;

    public function __construct() {
        $this->branchModel = new Branch();
    }

    public function index() {
        RoleMiddleware::requirePermission('branch.view');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $branches = $this->branchModel->searchAndFilter($filters, $limit, $offset);
        $totalBranches = $this->branchModel->countAll($filters);
        $totalPages = ceil($totalBranches / $limit);

        $this->view('branches.index', [
            'branches' => $branches,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('branch.create');
        $branchCode = $this->branchModel->generateBranchCode();
        $this->view('branches.create', ['branchCode' => $branchCode]);
    }

    public function store() {
        RoleMiddleware::requirePermission('branch.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/branches/create');
        }

        $data = [
            'branch_code' => $_POST['branch_code'],
            'branch_name' => htmlspecialchars($_POST['branch_name']),
            'phone' => htmlspecialchars($_POST['phone']),
            'email' => htmlspecialchars($_POST['email']),
            'manager_name' => htmlspecialchars($_POST['manager_name']),
            'country' => htmlspecialchars($_POST['country']),
            'division' => htmlspecialchars($_POST['division']),
            'district' => htmlspecialchars($_POST['district']),
            'upazila' => htmlspecialchars($_POST['upazila']),
            'postcode' => htmlspecialchars($_POST['postcode']),
            'address' => htmlspecialchars($_POST['address']),
            'status' => $_POST['status']
        ];

        if ($this->branchModel->create($data)) {
            logActivity('Branch Created', "Created branch: {$data['branch_name']} ({$data['branch_code']})");
            Session::flash('success', 'Branch created successfully.');
            $this->redirect('/branches');
        } else {
            Session::flash('error', 'Failed to create branch.');
            $this->redirect('/branches/create');
        }
    }

    public function edit() {
        RoleMiddleware::requirePermission('branch.edit');
        $id = $_GET['id'] ?? null;
        $branch = $this->branchModel->find($id);
        if (!$branch) $this->redirect('/branches');
        
        $this->view('branches.edit', ['branch' => $branch]);
    }

    public function update() {
        RoleMiddleware::requirePermission('branch.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/branches/edit?id=$id");
        }

        $data = [
            'branch_name' => htmlspecialchars($_POST['branch_name']),
            'phone' => htmlspecialchars($_POST['phone']),
            'email' => htmlspecialchars($_POST['email']),
            'manager_name' => htmlspecialchars($_POST['manager_name']),
            'country' => htmlspecialchars($_POST['country']),
            'division' => htmlspecialchars($_POST['division']),
            'district' => htmlspecialchars($_POST['district']),
            'upazila' => htmlspecialchars($_POST['upazila']),
            'postcode' => htmlspecialchars($_POST['postcode']),
            'address' => htmlspecialchars($_POST['address']),
            'status' => $_POST['status']
        ];

        if ($this->branchModel->update($id, $data)) {
            logActivity('Branch Updated', "Updated branch ID: $id");
            Session::flash('success', 'Branch updated successfully.');
            $this->redirect('/branches');
        } else {
            Session::flash('error', 'Failed to update branch.');
            $this->redirect("/branches/edit?id=$id");
        }
    }
}
