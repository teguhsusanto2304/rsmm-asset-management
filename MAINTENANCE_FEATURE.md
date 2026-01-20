# Corrective Maintenance Feature Documentation

## Overview
The Corrective Maintenance feature allows users to report maintenance issues for their assigned assets and enables technicians to provide detailed repair reports back to users.

## Features

### For Users (Asset Owners)
1. **Report Maintenance Issues**
   - Create maintenance requests for assigned assets
   - Choose maintenance type: Corrective (repair), Preventive (routine), or Emergency (critical)
   - Set priority levels: Low, Medium, High, Critical
   - Provide detailed description of the problem and symptoms
   - Estimate time needed for repair

2. **Track Maintenance Status**
   - View all maintenance reports they've created
   - See current status: Reported, Assigned, In Progress, Completed, Cancelled
   - View technician assignments
   - Access technician's completed work reports
   - View maintenance costs and work hours

3. **Edit Reports**
   - Modify reports before they are assigned to a technician
   - Update priority and description as needed

### For Technicians
1. **Receive Work Orders**
   - View maintenance requests assigned to them
   - See all relevant asset and issue information
   - Track priority and urgency

2. **Complete Maintenance**
   - Mark maintenance as "In Progress" when starting work
   - Record detailed work performed
   - Document parts replaced
   - Log actual working hours
   - Record labor and parts costs
   - Add notes for future maintenance

3. **Report Back to Users**
   - Automatically notify asset owners when maintenance is completed
   - Provide comprehensive maintenance report

### For Administrators/Managers
1. **Assign Technicians**
   - Assign reported maintenance to available technicians
   - Manage workload distribution

2. **View All Maintenance**
   - Access complete maintenance history
   - Generate reports and analytics
   - Monitor maintenance costs and trends

## Routes

```php
// User routes (all authenticated users)
GET     /master-data/maintenance                 # List maintenance records
GET     /master-data/maintenance/create          # Create new maintenance report
POST    /master-data/maintenance                 # Store maintenance report
GET     /master-data/maintenance/{id}            # View maintenance details
GET     /master-data/maintenance/{id}/edit       # Edit maintenance (if allowed)
PUT     /master-data/maintenance/{id}            # Update maintenance

// Technician routes
POST    /master-data/maintenance/{id}/start      # Start working on maintenance
POST    /master-data/maintenance/{id}/complete   # Complete and report

// Admin/Manager routes
POST    /master-data/maintenance/{id}/assign     # Assign to technician
POST    /master-data/maintenance/{id}/cancel     # Cancel maintenance

// API routes
GET     /master-data/maintenance/api/technicians # Get list of technicians
```

## Database Schema

### maintenance table
- `id` (UUID) - Primary key
- `asset_id` - Reference to asset
- `reported_by` - User who reported the issue
- `technician_id` - Assigned technician (nullable)
- `type` - Maintenance type (corrective, preventive, emergency)
- `status` - Current status (reported, assigned, in_progress, completed, cancelled)
- `priority` - Priority level (low, medium, high, critical)
- `issue_description` - Description of the problem
- `symptoms` - Symptoms observed
- `work_performed` - Work done by technician (nullable)
- `parts_replaced` - Parts replaced (nullable)
- `reported_date` - When issue was reported
- `assigned_date` - When assigned to technician
- `started_date` - When technician started work
- `completed_date` - When maintenance was completed
- `estimated_hours` - Estimated repair time
- `actual_hours` - Actual time spent (nullable)
- `labor_cost` - Cost of labor (nullable)
- `parts_cost` - Cost of parts (nullable)
- `total_cost` - Total maintenance cost (nullable)
- `notes` - Additional notes
- `next_maintenance_notes` - Recommendations for future maintenance
- `reference_number` - Unique ticket reference
- `attachments` - JSON array for file paths
- `created_by` - Admin creating record
- `updated_by` - Last user to update record
- Timestamps and soft deletes

## Models

### Maintenance Model
Located at: `app/Models/Maintenance.php`

Relations:
- `asset()` - The asset being maintained
- `reportedByUser()` - User who reported the issue
- `technician()` - Assigned technician
- `createdBy()` - Admin who created the record
- `updatedBy()` - Last user to update

Helper Methods:
- `getStatusLabel()` - Get localized status label
- `getTypeLabel()` - Get localized type label
- `getPriorityLabel()` - Get localized priority label
- `getPriorityColor()` - Get Tailwind color classes for priority
- `getStatusColor()` - Get Tailwind color classes for status
- `canBeEdited()` - Check if user can edit this record
- `canBeStarted()` - Check if technician can start work
- `canBeCompleted()` - Check if technician can complete work

Scopes:
- `reportedByUser($userId)` - Filter by reporting user
- `assignedToTechnician($technicianId)` - Filter by technician
- `active()` - Only active maintenance records
- `completed()` - Only completed maintenance

## Controller

### MaintenanceController
Located at: `app/Http/Controllers/MaintenanceController.php`

Key Methods:
- `index()` - List maintenance records (filtered by user role)
- `create()` - Show create form with user's assets
- `store()` - Create new maintenance record
- `show()` - Display maintenance details
- `edit()` - Edit maintenance (if allowed)
- `update()` - Update maintenance record
- `assign()` - Assign to technician (admin/manager only)
- `start()` - Start maintenance work (technician only)
- `complete()` - Complete and report maintenance (technician only)
- `cancel()` - Cancel maintenance record
- `getTechnicians()` - Return technicians list as JSON

## Views

### maintenance/index.blade.php
- List of all maintenance records
- Filter by status and statistics
- Quick actions for each record
- Pagination support

### maintenance/create.blade.php
- Form to report new maintenance issue
- Asset selection (only user's assigned assets)
- Maintenance type and priority selection
- Problem description and symptoms
- Estimated repair time

### maintenance/show.blade.php
- Detailed maintenance record view
- Asset information and condition
- Reported issue details
- Technician work report (if completed)
- Timeline of maintenance progress
- Cost breakdown
- Action buttons based on user role and maintenance status
- Modals for:
  - Assigning technician
  - Completing maintenance
  - Cancelling maintenance

### maintenance/edit.blade.php
- Edit form for maintenance records
- Only allows editing when status is 'reported' or 'assigned'
- Only asset owner or admin can edit

## Usage Examples

### 1. User Reports Maintenance Issue

1. Navigate to "Pemeliharaan" in sidebar
2. Click "Lapor Pemeliharaan" button
3. Select affected asset
4. Choose maintenance type (Corrective, Preventive, Emergency)
5. Set priority level
6. Describe the issue and symptoms
7. Estimate repair time (optional)
8. Click "Kirim Laporan"

### 2. Technician Receives and Completes Work

1. Navigate to "Pemeliharaan" in sidebar
2. View assigned maintenance requests
3. Click on a record to view details
4. Click "Mulai Mengerjakan" to start
5. When complete, click "Laporkan Selesai"
6. Fill in:
   - Work performed
   - Parts replaced
   - Actual hours worked
   - Labor cost
   - Parts cost
   - Maintenance notes for future
7. Click "Lapor Selesai"
8. System automatically notifies asset owner

### 3. Admin Assigns Maintenance

1. Navigate to "Pemeliharaan"
2. Find a "Dilaporkan" maintenance record
3. Click details to view full information
4. Click "Tugaskan ke Teknisi"
5. Select available technician
6. Click "Tugaskan"

## Authorization

- Users can only report maintenance for their own assigned assets
- Users can view their own maintenance records
- Technicians can view and work on assigned maintenance
- Technicians can only mark assigned maintenance as started/completed
- Admins/Managers can view all maintenance and assign technicians
- Only admins can cancel maintenance

## Status Workflow

```
Reported (initial)
    ↓
Assigned (technician assigned)
    ↓
In Progress (technician started work)
    ↓
Completed (work done, report submitted)

OR at any stage:
    ↓
Cancelled (issue resolved or withdrawn)
```

## Notifications

When maintenance is completed:
- Asset owner receives notification with technician's report
- Asset status automatically updated from "maintenance" to "available"
- Last maintenance date updated on asset record

## Integration with Assets

- When maintenance is reported as "Corrective" or "Emergency":
  - Asset status automatically changes to "maintenance"
- When maintenance is completed:
  - Asset status reverts to "available"
  - Last maintenance date is updated
  
## Future Enhancements

- Email notifications to users and technicians
- File upload for maintenance evidence (photos, documents)
- Maintenance history analytics and reporting
- Preventive maintenance scheduling
- Asset maintenance cost analytics
- Parts inventory integration
- Technician performance metrics
- Scheduled maintenance reminders
