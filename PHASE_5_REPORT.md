# Phase 5 Completion Report: Parcel Booking Foundation

Phase 5 of the **Courier Management System** has been successfully implemented, establishing the core **Parcel Booking Foundation**. This phase enables the administrative team to book parcels, manage receiver information, and track the initial booking status.

## Database Schema
Two new tables were introduced to handle the parcel lifecycle and receiver details, maintaining strict referential integrity.

### `parcels` Table
- **Identifiers**: `id`, `tracking_number` (Unique), `invoice_number` (Unique).
- **Relationships**: `customer_id` (Sender), `receiver_id` (Recipient), `created_by` (Staff).
- **Attributes**: `parcel_type`, `delivery_type`, `weight`, `quantity`, `declared_value`.
- **Financials**: `delivery_charge`, `cod_amount`.
- **Status & Auditing**: `current_status` (Default: 'Booked'), `booking_date`, `created_at`, `updated_at`, `deleted_at`.

### `parcel_receivers` Table
Stores comprehensive contact and location details for recipients, including `receiver_name`, `phone`, `address`, `district`, and `landmark`.

## Core Features Implemented

### 1. Parcel Booking Workflow
- **Auto-Generation**: Implemented sequential, unique **Tracking Numbers** (`TRK-YYYYXXXXXX`) and **Invoice Numbers** (`INV-YYYYXXXXXX`).
- **Booking Form**: A professional, split-layout form for entering sender (existing customer), receiver details, and parcel specifics.
- **Receiver Management**: Integrated logic to save new receiver information for every parcel booking.

### 2. Management & Visibility
- **Parcel List**: A professional dashboard table featuring search (by tracking, invoice, names, or phone) and advanced filtering (by status, type, and date).
- **Details View**: A comprehensive profile page for each parcel, clearly separating sender, receiver, and shipment information.
- **Soft Delete**: Enables safe removal of parcel records without losing database history.

### 3. Security & Integration
- **RBAC Protection**: Added `parcel.view`, `parcel.create`, `parcel.edit`, and `parcel.delete` permissions.
- **Sidebar Integration**: The "Parcels" menu item dynamically appears based on the user's role.
- **Activity Logging**: Automated tracking for all parcel-related actions (Created, Updated, Deleted).

## Files Created/Modified

### New Files
- `app/Models/Parcel.php`: Core logic for parcel management and number generation.
- `app/Models/Receiver.php`: Handles recipient data storage.
- `app/Controllers/ParcelController.php`: Manages booking workflows and CRUD operations.
- `app/Views/parcels/index.php`: Professional list view with search/filter.
- `app/Views/parcels/create.php`: Split-layout booking form.
- `app/Views/parcels/edit.php`: Form for updating booking details.
- `app/Views/parcels/show.php`: Detailed parcel information view.
- `database/sql/007_parcels.sql`: Database schema and initial permissions.

### Modified Files
- `routes/web.php`: Added parcel-related routing.
- `app/Views/layouts/header.php`: Integrated Parcels link into the sidebar.

## Verification Results
| Feature | Status | Verification Detail |
| :--- | :--- | :--- |
| **Parcel CRUD** | PASS | Full lifecycle (Booking, Viewing, Editing, Deletion) verified. |
| **Number Generation** | PASS | Unique `TRK-` and `INV-` formats correctly generated. |
| **Relationships** | PASS | Successful linking of Customer, Receiver, and Creator User. |
| **Search & Filter** | PASS | Multi-criteria search and status/type filtering verified. |
| **RBAC & Logging** | PASS | Permission-based access and automated activity logs verified. |

---
*Phase 5 is complete. The system is now ready for future modules such as Parcel Tracking, Rider Assignment, and Delivery Processing.*
