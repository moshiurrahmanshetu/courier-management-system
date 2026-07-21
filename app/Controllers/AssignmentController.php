<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Assignment;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\Tracking;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class AssignmentController extends Controller {
    private $assignmentModel;
    private $parcelModel;
    private $riderModel;
    private $trackingModel;

    public function __construct() {
        $this->assignmentModel = new Assignment();
        $this->parcelModel = new Parcel();
        $this->riderModel = new Rider();
        $this->trackingModel = new Tracking();
    }

    public function index() {
        RoleMiddleware::requirePermission('assignment.view');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'type' => $_GET['type'] ?? ''
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $assignments = $this->assignmentModel->searchAndFilter($filters, $limit, $offset);
        $totalAssignments = $this->assignmentModel->countAll($filters);
        $totalPages = ceil($totalAssignments / $limit);

        $this->view('assignments.index', [
            'assignments' => $assignments,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('assignment.create');
        
        $parcels = $this->parcelModel->searchAndFilter(['status' => 'Booked'], 100, 0);
        $riders = $this->riderModel->searchAndFilter(['status' => 'active'], 100, 0);

        $this->view('assignments.create', [
            'parcels' => $parcels,
            'riders' => $riders
        ]);
    }

    public function store() {
        RoleMiddleware::requirePermission('assignment.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/assignments/create');
        }

        $parcelId = $_POST['parcel_id'];
        $type = $_POST['assignment_type'];

        if ($this->assignmentModel->hasActiveAssignment($parcelId, $type)) {
            Session::flash('error', "This parcel already has an active $type assignment.");
            $this->redirect('/assignments/create');
        }

        $data = [
            'parcel_id' => $parcelId,
            'rider_id' => $_POST['rider_id'],
            'assignment_type' => $type,
            'assigned_by' => $_SESSION['user_id'],
            'remarks' => htmlspecialchars($_POST['remarks'])
        ];

        if ($this->assignmentModel->create($data)) {
            // Tracking Integration
            $status = ($type === 'pickup') ? 'Pickup Rider Assigned' : 'Delivery Rider Assigned';
            $this->trackingModel->addLog([
                'parcel_id' => $parcelId,
                'branch_id' => null, // Will be updated by rider if needed
                'status' => $status,
                'remarks' => "Rider Assigned: " . $_POST['rider_id'],
                'updated_by' => $_SESSION['user_id']
            ]);

            logActivity('Parcel Assigned', "Assigned parcel ID: $parcelId to rider ID: {$_POST['rider_id']} for $type");
            Session::flash('success', 'Parcel assigned successfully.');
            $this->redirect('/assignments');
        } else {
            Session::flash('error', 'Failed to assign parcel.');
            $this->redirect('/assignments/create');
        }
    }

    public function updateStatus() {
        // Riders can update their own assignments
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        $remarks = $_POST['remarks'] ?? '';

        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            Session::flash('error', 'Assignment not found.');
            $this->redirect('/dashboard');
        }

        if ($this->assignmentModel->updateStatus($id, $status, $remarks)) {
            // Tracking Integration
            $trackingStatus = $assignment['assignment_type'] === 'pickup' ? "Pickup $status" : "Delivery $status";
            
            // If status is completed, update parcel status accordingly
            if ($status === 'Completed') {
                $finalStatus = ($assignment['assignment_type'] === 'pickup') ? 'Picked Up' : 'Delivered';
                $trackingStatus = $finalStatus;
            }

            $this->trackingModel->addLog([
                'parcel_id' => $assignment['parcel_id'],
                'branch_id' => null,
                'status' => $trackingStatus,
                'remarks' => $remarks,
                'updated_by' => $_SESSION['user_id']
            ]);

            logActivity("Assignment $status", "Assignment ID: $id status changed to $status");
            Session::flash('success', "Assignment $status successfully.");
        } else {
            Session::flash('error', 'Failed to update assignment status.');
        }

        // Redirect based on role
        if ($_SESSION['role_slug'] === 'super-admin' || $_SESSION['role_slug'] === 'admin') {
            $this->redirect('/assignments');
        } else {
            $this->redirect('/rider/dashboard');
        }
    }
}
