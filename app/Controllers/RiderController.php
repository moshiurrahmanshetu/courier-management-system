<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Rider;
use App\Models\Branch;
use App\Models\User;
use App\Models\Assignment;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class RiderController extends Controller {
    private $riderModel;
    private $branchModel;
    private $userModel;
    private $assignmentModel;

    public function __construct() {
        $this->riderModel = new Rider();
        $this->branchModel = new Branch();
        $this->userModel = new User();
        $this->assignmentModel = new Assignment();
    }

    public function index() {
        RoleMiddleware::requirePermission('rider.view');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'branch_id' => $_GET['branch_id'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $riders = $this->riderModel->searchAndFilter($filters, $limit, $offset);
        $totalRiders = $this->riderModel->countAll($filters);
        $totalPages = ceil($totalRiders / $limit);
        $branches = $this->branchModel->searchAndFilter(['status' => 'active'], 100, 0);

        $this->view('riders.index', [
            'riders' => $riders,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'branches' => $branches
        ]);
    }

    public function dashboard() {
        $rider = $this->riderModel->findByUserId($_SESSION['user_id']);
        if (!$rider) {
            Session::flash('error', 'Rider profile not found.');
            $this->redirect('/dashboard');
        }

        $stats = $this->assignmentModel->getRiderStats($rider['id']);
        $tasks = $this->assignmentModel->getRiderTasks($rider['id'], 'Assigned');
        $activeTasks = $this->assignmentModel->getRiderTasks($rider['id'], 'In Progress');
        $acceptedTasks = $this->assignmentModel->getRiderTasks($rider['id'], 'Accepted');

        $this->view('riders.dashboard', [
            'rider' => $rider,
            'stats' => $stats,
            'tasks' => array_merge($tasks, $acceptedTasks, $activeTasks)
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('rider.create');
        $riderCode = $this->riderModel->generateRiderCode();
        $branches = $this->branchModel->searchAndFilter(['status' => 'active'], 100, 0);
        
        // Only show users who are not already riders or customers
        $db = \Core\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT u.id, u.name FROM users u 
                           LEFT JOIN riders r ON u.id = r.user_id 
                           LEFT JOIN customers c ON u.id = c.user_id 
                           WHERE r.id IS NULL AND c.id IS NULL AND u.deleted_at IS NULL");
        $users = $stmt->fetchAll();

        $this->view('riders.create', [
            'riderCode' => $riderCode,
            'branches' => $branches,
            'users' => $users
        ]);
    }

    public function store() {
        RoleMiddleware::requirePermission('rider.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/riders/create');
        }

        $data = [
            'user_id' => $_POST['user_id'],
            'rider_code' => $_POST['rider_code'],
            'branch_id' => $_POST['branch_id'],
            'vehicle_type' => htmlspecialchars($_POST['vehicle_type']),
            'license_number' => htmlspecialchars($_POST['license_number']),
            'nid_number' => htmlspecialchars($_POST['nid_number']),
            'joining_date' => $_POST['joining_date'],
            'status' => $_POST['status']
        ];

        if ($this->riderModel->create($data)) {
            logActivity('Rider Created', "Created rider profile for user ID: {$data['user_id']}");
            Session::flash('success', 'Rider created successfully.');
            $this->redirect('/riders');
        } else {
            Session::flash('error', 'Failed to create rider.');
            $this->redirect('/riders/create');
        }
    }

    public function edit() {
        RoleMiddleware::requirePermission('rider.edit');
        $id = $_GET['id'] ?? null;
        $rider = $this->riderModel->find($id);
        if (!$rider) $this->redirect('/riders');
        
        $branches = $this->branchModel->searchAndFilter(['status' => 'active'], 100, 0);
        $this->view('riders.edit', ['rider' => $rider, 'branches' => $branches]);
    }

    public function update() {
        RoleMiddleware::requirePermission('rider.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/riders/edit?id=$id");
        }

        $data = [
            'branch_id' => $_POST['branch_id'],
            'vehicle_type' => htmlspecialchars($_POST['vehicle_type']),
            'license_number' => htmlspecialchars($_POST['license_number']),
            'nid_number' => htmlspecialchars($_POST['nid_number']),
            'joining_date' => $_POST['joining_date'],
            'status' => $_POST['status']
        ];

        if ($this->riderModel->update($id, $data)) {
            logActivity('Rider Updated', "Updated rider ID: $id");
            Session::flash('success', 'Rider updated successfully.');
            $this->redirect('/riders');
        } else {
            Session::flash('error', 'Failed to update rider.');
            $this->redirect("/riders/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('rider.delete');
        $id = $_POST['id'] ?? null;
        if ($this->riderModel->softDelete($id)) {
            logActivity('Rider Deleted', "Soft-deleted rider ID: $id");
            Session::flash('success', 'Rider deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete rider.');
        }
        $this->redirect('/riders');
    }
}
