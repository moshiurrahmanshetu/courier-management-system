<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Parcel;
use App\Models\Tracking;
use App\Models\Branch;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class TrackingController extends Controller {
    private $parcelModel;
    private $trackingModel;
    private $branchModel;

    public function __construct() {
        $this->parcelModel = new Parcel();
        $this->trackingModel = new Tracking();
        $this->branchModel = new Branch();
    }

    public function index() {
        RoleMiddleware::requirePermission('tracking.view');
        $trackingNumber = $_GET['tracking_number'] ?? null;
        
        if ($trackingNumber) {
            $parcel = $this->parcelModel->searchAndFilter(['search' => $trackingNumber], 1, 0);
            if (!empty($parcel)) {
                $this->redirect("/tracking/timeline?id=" . $parcel[0]['id']);
            } else {
                Session::flash('error', 'Tracking number not found.');
            }
        }
        
        $this->view('tracking.index');
    }

    public function timeline() {
        RoleMiddleware::requirePermission('tracking.view');
        $id = $_GET['id'] ?? null;
        $parcel = $this->parcelModel->find($id);
        
        if (!$parcel) {
            Session::flash('error', 'Parcel not found.');
            $this->redirect('/parcels');
        }

        // Customer Access Restriction
        if ($_SESSION['role_slug'] === 'general-user') {
            // Check if the parcel belongs to this customer
            // We need the customer_id linked to the current user
            $db = \Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM customers WHERE user_id = ? LIMIT 1");
            $stmt->execute([$_SESSION['user_id']]);
            $customer = $stmt->fetch();
            
            if (!$customer || $parcel['customer_id'] != $customer['id']) {
                $this->redirect('/errors/403');
            }
        }

        $logs = $this->trackingModel->getLogsByParcelId($id);
        $branches = $this->branchModel->searchAndFilter(['status' => 'active'], 100, 0);

        $this->view('tracking.timeline', [
            'parcel' => $parcel,
            'logs' => $logs,
            'branches' => $branches
        ]);
    }

    public function update() {
        RoleMiddleware::requirePermission('tracking.update');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/tracking/timeline?id=" . $_POST['parcel_id']);
        }

        $data = [
            'parcel_id' => $_POST['parcel_id'],
            'branch_id' => !empty($_POST['branch_id']) ? $_POST['branch_id'] : null,
            'status' => $_POST['status'],
            'remarks' => htmlspecialchars($_POST['remarks']),
            'updated_by' => $_SESSION['user_id']
        ];

        if ($this->trackingModel->addLog($data)) {
            logActivity('Tracking Updated', "Updated status to '{$data['status']}' for parcel ID: {$data['parcel_id']}");
            Session::flash('success', 'Tracking status updated successfully.');
        } else {
            Session::flash('error', 'Failed to update tracking status.');
        }

        $this->redirect("/tracking/timeline?id=" . $data['parcel_id']);
    }
}
