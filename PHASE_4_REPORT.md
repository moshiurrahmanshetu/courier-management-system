# Phase 4 Completion Report: Customer Management

Phase 4 of the **Courier Management System** has been successfully implemented, delivering a complete **Customer Management** module. This module establishes a structured way to manage business entities and individuals linked to user accounts.

## Database Enhancements
A new `customers` table was created to store detailed business and contact information, linked to the `users` table via a one-to-one relationship.

### `customers` Table Highlights
- **Identifiers**: `id`, `user_id` (Unique FK), `customer_code` (Unique).
- **Business Details**: `company_name`, `customer_type` (Individual/Business), `trade_license`.
- **Contact Info**: `contact_person`, `phone`, `alternative_phone`, `email`, `nid_number`.
- **Location**: `country`, `division`, `district`, `upazila`, `postcode`, `address`.
- **Status & Auditing**: `status` (Active/Inactive), `notes`, `created_at`, `updated_at`, `deleted_at` (Soft Delete).

## Core Features Implemented

### 1. Customer CRUD & Management
- **Auto Customer Code**: Implemented logic to automatically generate unique codes like `CUS-000001`.
- **User Relationship**: Strict one-to-one mapping. The system ensures a user can only be linked to a single customer profile.
- **Soft Delete & Restore**: Customers can be soft-deleted (hidden from main views) and restored by administrators when needed.
- **Detailed Profile**: A professional view showing all business, contact, and location data alongside the linked user's information.

### 2. Advanced Search & Filtering
- **Search**: Real-time search by customer code, company name, contact person, phone, or email.
- **Filtering**: Ability to filter the list by customer type (Individual/Business) and status (Active/Inactive).
- **View Options**: Admins can toggle the visibility of soft-deleted records.

### 3. Security & RBAC Integration
- **Permissions**: Added `customer.view`, `customer.create`, `customer.edit`, and `customer.delete`.
- **Middleware**: Every action is protected by the `RoleMiddleware`.
- **Sidebar Integration**: The "Customers" menu item dynamically appears based on the user's permissions.
- **Activity Logging**: Automated tracking for customer creation, updates, deletions, and restorations.

## Files Created/Modified

### New Files
- `app/Models/Customer.php`: Core logic for customer data and relationships.
- `app/Controllers/CustomerController.php`: Handles CRUD operations and filtering.
- `app/Views/customers/index.php`: Professional list view with search/filter.
- `app/Views/customers/create.php`: Form for creating new customers.
- `app/Views/customers/edit.php`: Form for updating customer details.
- `app/Views/customers/show.php`: Detailed customer profile view.
- `database/sql/008_customers.sql`: Database schema and permissions.

### Modified Files
- `routes/web.php`: Added all customer-related routes.
- `app/Views/layouts/header.php`: Integrated Customers link into the sidebar.

## Verification Results
| Feature | Status | Verification Detail |
| :--- | :--- | :--- |
| **Customer CRUD** | PASS | Full lifecycle (Create, Read, Update, Soft Delete) verified. |
| **User Mapping** | PASS | One-to-one relationship and validation enforced. |
| **Auto-Code** | PASS | Sequential `CUS-XXXXXX` generation verified. |
| **Soft Delete/Restore** | PASS | `deleted_at` logic and restoration process verified. |
| **Search & Filter** | PASS | Multi-criteria search and type/status filtering verified. |
| **Activity Log** | PASS | Automatic logging of all customer events verified. |
| **Permissions** | PASS | RBAC route protection and sidebar visibility verified. |

---
*Phase 4 is complete. The system now supports robust customer entity management, providing a solid base for future parcel and delivery modules.*
