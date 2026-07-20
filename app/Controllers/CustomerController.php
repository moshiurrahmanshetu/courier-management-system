<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\RoleMiddleware;

class CustomerController extends Controller {
    private $customerModel;
    private $userModel;

    public function __construct() {
        $this->customerModel = new Customer();
        $this->userModel = new User();
    }

    public function index() {
        RoleMiddleware::requirePermission('customer.view');
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'customer_type' => $_GET['customer_type'] ?? '',
            'include_deleted' => isset($_GET['show_deleted']) && $_GET['show_deleted'] == 1
        ];

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $customers = $this->customerModel->searchAndFilter($filters, $limit, $offset);
        $totalCustomers = $this->customerModel->countAll($filters);
        $totalPages = ceil($totalCustomers / $limit);

        $this->view('customers.index', [
            'customers' => $customers,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create() {
        RoleMiddleware::requirePermission('customer.create');
        // Get all users who are not already assigned to a customer
        $stmt = \Core\Database::getInstance()->getConnection()->query(
            "SELECT u.id, u.name, u.email FROM users u 
             LEFT JOIN customers c ON u.id = c.user_id 
             WHERE c.id IS NULL AND u.deleted_at IS NULL"
        );
        $availableUsers = $stmt->fetchAll();
        
        $customerCode = $this->customerModel->generateCustomerCode();
        
        $this->view('customers.create', [
            'availableUsers' => $availableUsers,
            'customerCode' => $customerCode
        ]);
    }

    public function store() {
        RoleMiddleware::requirePermission('customer.create');
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/customers/create');
        }

        $userId = $_POST['user_id'];
        if ($this->customerModel->isUserAssigned($userId)) {
            Session::flash('error', 'Selected user is already assigned to a customer.');
            $this->redirect('/customers/create');
        }

        $data = [
            'user_id' => $userId,
            'customer_code' => $_POST['customer_code'],
            'company_name' => htmlspecialchars($_POST['company_name']),
            'contact_person' => htmlspecialchars($_POST['contact_person']),
            'phone' => htmlspecialchars($_POST['phone']),
            'alternative_phone' => htmlspecialchars($_POST['alternative_phone']),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'nid_number' => htmlspecialchars($_POST['nid_number']),
            'trade_license' => htmlspecialchars($_POST['trade_license']),
            'country' => htmlspecialchars($_POST['country']),
            'division' => htmlspecialchars($_POST['division']),
            'district' => htmlspecialchars($_POST['district']),
            'upazila' => htmlspecialchars($_POST['upazila']),
            'postcode' => htmlspecialchars($_POST['postcode']),
            'address' => htmlspecialchars($_POST['address']),
            'customer_type' => $_POST['customer_type'],
            'status' => $_POST['status'] ?? 'active',
            'notes' => htmlspecialchars($_POST['notes'])
        ];

        if ($this->customerModel->create($data)) {
            logActivity('Customer Created', "Created customer code: {$data['customer_code']}");
            Session::flash('success', 'Customer created successfully.');
            $this->redirect('/customers');
        } else {
            Session::flash('error', 'Failed to create customer.');
            $this->redirect('/customers/create');
        }
    }

    public function show() {
        RoleMiddleware::requirePermission('customer.view');
        $id = $_GET['id'] ?? null;
        $customer = $this->customerModel->find($id);
        if (!$customer) $this->redirect('/customers');
        
        $this->view('customers.show', ['customer' => $customer]);
    }

    public function edit() {
        RoleMiddleware::requirePermission('customer.edit');
        $id = $_GET['id'] ?? null;
        $customer = $this->customerModel->find($id);
        if (!$customer) $this->redirect('/customers');
        
        $this->view('customers.edit', ['customer' => $customer]);
    }

    public function update() {
        RoleMiddleware::requirePermission('customer.edit');
        $id = $_POST['id'] ?? null;
        
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect("/customers/edit?id=$id");
        }

        $data = [
            'company_name' => htmlspecialchars($_POST['company_name']),
            'contact_person' => htmlspecialchars($_POST['contact_person']),
            'phone' => htmlspecialchars($_POST['phone']),
            'alternative_phone' => htmlspecialchars($_POST['alternative_phone']),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'nid_number' => htmlspecialchars($_POST['nid_number']),
            'trade_license' => htmlspecialchars($_POST['trade_license']),
            'country' => htmlspecialchars($_POST['country']),
            'division' => htmlspecialchars($_POST['division']),
            'district' => htmlspecialchars($_POST['district']),
            'upazila' => htmlspecialchars($_POST['upazila']),
            'postcode' => htmlspecialchars($_POST['postcode']),
            'address' => htmlspecialchars($_POST['address']),
            'customer_type' => $_POST['customer_type'],
            'status' => $_POST['status'],
            'notes' => htmlspecialchars($_POST['notes'])
        ];

        if ($this->customerModel->update($id, $data)) {
            logActivity('Customer Updated', "Updated customer ID: $id");
            Session::flash('success', 'Customer updated successfully.');
            $this->redirect('/customers');
        } else {
            Session::flash('error', 'Failed to update customer.');
            $this->redirect("/customers/edit?id=$id");
        }
    }

    public function delete() {
        RoleMiddleware::requirePermission('customer.delete');
        $id = $_POST['id'] ?? null;
        
        if ($this->customerModel->softDelete($id)) {
            logActivity('Customer Deleted', "Soft-deleted customer ID: $id");
            Session::flash('success', 'Customer deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete customer.');
        }
        $this->redirect('/customers');
    }

    public function restore() {
        RoleMiddleware::requirePermission('customer.delete');
        $id = $_POST['id'] ?? null;
        
        if ($this->customerModel->restore($id)) {
            logActivity('Customer Restored', "Restored customer ID: $id");
            Session::flash('success', 'Customer restored successfully.');
        } else {
            Session::flash('error', 'Failed to restore customer.');
        }
        $this->redirect('/customers?show_deleted=1');
    }
}
