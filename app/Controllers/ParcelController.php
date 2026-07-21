<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Parcel;
use App\Models\Receiver;
use App\Models\Customer;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class ParcelController extends Controller {
    private $parcelModel;
    private $receiverModel;
    private $customerModel;

    public function __construct() {
        $this->parcelModel = new Parcel();
        $this->receiverModel = new Receiver();
        $this->customerModel = new Customer();
    }

    public function index() {
        RoleMiddleware::requirePermission('parcel.view');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'parcel_type' => $_GET['parcel_type'] ?? '',
            'delivery_type' => $_GET['delivery_type'] ?? '',
            'booking_date' => $_GET['booking_date'] ?? ''
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $parcels = $this->parcelModel->searchAndFilter($filters, $limit, $offset);
        $totalParcels = $this->parcelModel->countAll($filters);
        $totalPages = ceil($totalParcels / $limit);

        $this->view('parcels.index', [
            'parcels' => $parcels,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('parcel.create');
        $customers = $this->customerModel->searchAndFilter(['status' => 'active'], 100, 0);
        $trackingNumber = $this->parcelModel->generateTrackingNumber();
        $invoiceNumber = $this->parcelModel->generateInvoiceNumber();
        
        $this->view('parcels.create', [
            'customers' => $customers,
            'trackingNumber' => $trackingNumber,
            'invoiceNumber' => $invoiceNumber
        ]);
    }

    public function store() {
        RoleMiddleware::requirePermission('parcel.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/parcels/create');
        }

        // 1. Create Receiver
        $receiverData = [
            'receiver_name' => htmlspecialchars($_POST['receiver_name']),
            'phone' => htmlspecialchars($_POST['receiver_phone']),
            'alternative_phone' => htmlspecialchars($_POST['receiver_alt_phone']),
            'country' => htmlspecialchars($_POST['receiver_country']),
            'division' => htmlspecialchars($_POST['receiver_division']),
            'district' => htmlspecialchars($_POST['receiver_district']),
            'upazila' => htmlspecialchars($_POST['receiver_upazila']),
            'postcode' => htmlspecialchars($_POST['receiver_postcode']),
            'address' => htmlspecialchars($_POST['receiver_address']),
            'landmark' => htmlspecialchars($_POST['receiver_landmark'])
        ];
        $receiverId = $this->receiverModel->create($receiverData);

        if (!$receiverId) {
            Session::flash('error', 'Failed to save receiver information.');
            $this->redirect('/parcels/create');
        }

        // 2. Create Parcel
        $parcelData = [
            'tracking_number' => $_POST['tracking_number'],
            'invoice_number' => $_POST['invoice_number'],
            'customer_id' => $_POST['customer_id'],
            'receiver_id' => $receiverId,
            'parcel_type' => $_POST['parcel_type'],
            'delivery_type' => $_POST['delivery_type'],
            'weight' => (float)$_POST['weight'],
            'quantity' => (int)$_POST['quantity'],
            'declared_value' => (float)$_POST['declared_value'],
            'delivery_charge' => (float)$_POST['delivery_charge'],
            'cod_amount' => (float)$_POST['cod_amount'],
            'special_instruction' => htmlspecialchars($_POST['special_instruction']),
            'current_status' => 'Booked',
            'booking_date' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id']
        ];

        if ($this->parcelModel->create($parcelData)) {
            logActivity('Parcel Created', "Booked parcel: {$parcelData['tracking_number']}");
            Session::flash('success', 'Parcel booked successfully.');
            $this->redirect('/parcels');
        } else {
            Session::flash('error', 'Failed to book parcel.');
            $this->redirect('/parcels/create');
        }
    }

    public function show() {
        RoleMiddleware::requirePermission('parcel.view');
        $id = $_GET['id'] ?? null;
        $parcel = $this->parcelModel->find($id);
        if (!$parcel) $this->redirect('/parcels');
        
        $this->view('parcels.show', ['parcel' => $parcel]);
    }

    public function edit() {
        RoleMiddleware::requirePermission('parcel.edit');
        $id = $_GET['id'] ?? null;
        $parcel = $this->parcelModel->find($id);
        if (!$parcel) $this->redirect('/parcels');
        
        $this->view('parcels.edit', ['parcel' => $parcel]);
    }

    public function update() {
        RoleMiddleware::requirePermission('parcel.edit');
        $id = $_POST['id'] ?? null;
        $receiverId = $_POST['receiver_id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/parcels/edit?id=$id");
        }

        // Update Receiver
        $receiverData = [
            'receiver_name' => htmlspecialchars($_POST['receiver_name']),
            'phone' => htmlspecialchars($_POST['receiver_phone']),
            'alternative_phone' => htmlspecialchars($_POST['receiver_alt_phone']),
            'country' => htmlspecialchars($_POST['receiver_country']),
            'division' => htmlspecialchars($_POST['receiver_division']),
            'district' => htmlspecialchars($_POST['receiver_district']),
            'upazila' => htmlspecialchars($_POST['receiver_upazila']),
            'postcode' => htmlspecialchars($_POST['receiver_postcode']),
            'address' => htmlspecialchars($_POST['receiver_address']),
            'landmark' => htmlspecialchars($_POST['receiver_landmark'])
        ];
        $this->receiverModel->update($receiverId, $receiverData);

        // Update Parcel
        $parcelData = [
            'parcel_type' => $_POST['parcel_type'],
            'delivery_type' => $_POST['delivery_type'],
            'weight' => (float)$_POST['weight'],
            'quantity' => (int)$_POST['quantity'],
            'declared_value' => (float)$_POST['declared_value'],
            'delivery_charge' => (float)$_POST['delivery_charge'],
            'cod_amount' => (float)$_POST['cod_amount'],
            'special_instruction' => htmlspecialchars($_POST['special_instruction'])
        ];

        if ($this->parcelModel->update($id, $parcelData)) {
            logActivity('Parcel Updated', "Updated parcel ID: $id");
            Session::flash('success', 'Parcel updated successfully.');
            $this->redirect('/parcels');
        } else {
            Session::flash('error', 'Failed to update parcel.');
            $this->redirect("/parcels/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('parcel.delete');
        $id = $_POST['id'] ?? null;
        
        if ($this->parcelModel->softDelete($id)) {
            logActivity('Parcel Deleted', "Soft-deleted parcel ID: $id");
            Session::flash('success', 'Parcel deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete parcel.');
        }
        $this->redirect('/parcels');
    }
}
