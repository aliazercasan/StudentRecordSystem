# Student Record System - Workflow Documentation

## Project Overview
A Laravel-based student record management system with authentication, CRUD operations, activity logging, photo management, and data export capabilities.

## System Architecture

### Core Components
- **Framework**: Laravel 11.x
- **Database**: SQLite
- **Frontend**: Blade templates with Bootstrap 5
- **Build Tool**: Vite
- **Testing**: Pest PHP

## User Workflows

### 1. Authentication Flow
```
Login Page → Validate Credentials → Dashboard
     ↓
  Invalid → Show Errors → Retry
     ↓
  Logout → Terminate Session → Login Page
```

**Files Involved**:
- `app/Http/Controllers/AuthController.php`
- `app/Http/Requests/LoginRequest.php`
- `resources/views/auth/login.blade.php`

### 2. Student Management Workflow

#### Create Student
```
Students List → Create Button → Form → Validate → Save → Activity Log → Redirect to List
```

**Process**:
1. User clicks "Add Student" from index page
2. Form displays with fields: student_id, name, email, course, year_level, photo
3. Validation enforces required fields and unique student_id
4. Photo uploaded to `storage/app/public/photos`
5. Activity log records creation
6. Redirects to student list with success message

**Files Involved**:
- `app/Http/Controllers/StudentController.php@create()`
- `app/Http/Requests/StoreStudentRequest.php`
- `app/Services/FileUploadService.php`
- `resources/views/students/create.blade.php`


#### View Student Details
```
Students List → Click Student → Detail View (shows all fields + photo)
```

**Files Involved**:
- `app/Http/Controllers/StudentController.php@show()`
- `resources/views/students/show.blade.php`

#### Update Student
```
Detail View → Edit Button → Pre-filled Form → Modify → Validate → Update → Activity Log → Redirect
```

**Process**:
1. Form pre-populated with existing data
2. Photo can be replaced (old photo deleted)
3. Timestamps preserved on update
4. Activity log records modification
5. Validation prevents duplicate student_id

**Files Involved**:
- `app/Http/Controllers/StudentController.php@edit(), update()`
- `app/Http/Requests/UpdateStudentRequest.php`
- `resources/views/students/edit.blade.php`

#### Delete Student
```
List/Detail View → Delete Button → Confirm → Remove Record → Delete Photo → Activity Log → Redirect
```

**Files Involved**:
- `app/Http/Controllers/StudentController.php@destroy()`

### 3. Dashboard Workflow
```
Login Success → Dashboard → Display Statistics
```

**Statistics Displayed**:
- Total student count
- Students grouped by year level
- Students grouped by course

**Files Involved**:
- `app/Http/Controllers/DashboardController.php`
- `resources/views/dashboard.blade.php`


### 4. Search & Filter Workflow
```
Students List → Enter Search Term → Filter Results (by name, email, student_id, course)
```

**Features**:
- Real-time search across multiple fields
- Maintains pagination
- Preserves search state in URL

**Files Involved**:
- `app/Http/Controllers/StudentController.php@index()`
- `resources/views/students/index.blade.php`

### 5. Export Workflow

#### Excel Export
```
Students List → Export Excel Button → Generate File → Download
```

#### PDF Export
```
Students List → Export PDF Button → Generate File → Download
```

**Features**:
- Respects current search filters
- Includes all student fields
- Proper column headers

**Files Involved**:
- `app/Services/ExportService.php`
- `app/Http/Controllers/StudentController.php@exportExcel(), exportPdf()`

### 6. Activity Log Workflow
```
Any CRUD Operation → Log Entry Created → View Activity Logs Page
```

**Logged Actions**:
- Student created
- Student updated
- Student deleted
- Login/logout events

**Log Fields**:
- Action type
- User who performed action
- Student affected
- Timestamp
- Description

**Display**:
- Chronological order (newest first)
- Paginated list
- Filterable by action type

**Files Involved**:
- `app/Services/ActivityLogService.php`
- `app/Http/Controllers/ActivityLogController.php`
- `app/Models/ActivityLog.php`
- `resources/views/activity-logs/index.blade.php`


## Technical Workflows

### Photo Management
```
Upload → Validate (jpg, jpeg, png, max 2MB) → Store in storage/app/public/photos → Save path to DB
Update → Delete old photo → Upload new → Update path
Delete Student → Remove photo file → Delete record
```

**Service**: `app/Services/FileUploadService.php`

### Validation Flow
```
Form Submit → Request Validation → Pass → Controller Action
                                  ↓
                                Fail → Return with Errors → Display in Form
```

**Validation Rules**:
- **student_id**: required, unique, max 20 chars
- **name**: required, max 255 chars
- **email**: required, email format, max 255 chars
- **course**: required, max 100 chars
- **year_level**: required, integer, 1-5
- **photo**: optional, image (jpg/jpeg/png), max 2MB

### Database Schema

#### students
- id (primary key)
- student_id (unique)
- name
- email
- course
- year_level
- photo (nullable)
- timestamps

#### activity_logs
- id (primary key)
- user_id (foreign key)
- student_id (nullable, foreign key)
- action
- description
- timestamps

#### users
- id (primary key)
- name
- email (unique)
- password
- timestamps


## Security Features

### Authentication
- Session-based authentication
- Password hashing (bcrypt)
- Login throttling
- Logout session termination

### Authorization
- All routes protected except login
- Middleware: `auth` applied to all student routes
- CSRF token validation on all forms

### Input Validation
- Server-side validation on all inputs
- XSS prevention via Blade escaping
- SQL injection prevention via Eloquent ORM
- File upload validation

### Data Protection
- HTML entity escaping in views
- Secure file storage
- Environment variables for sensitive config

## Development Workflow

### Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run dev
php artisan serve
```

### Testing
```bash
php artisan test                    # Run all tests
php artisan test --filter Feature   # Feature tests only
php artisan test --filter Unit      # Unit tests only
```

**Test Coverage**:
- 40+ Feature tests covering all workflows
- Unit tests for services
- Authentication tests
- Validation tests
- CRUD operation tests
- Export functionality tests


## File Structure Overview

### Controllers
- `AuthController.php` - Login/logout handling
- `DashboardController.php` - Statistics display
- `StudentController.php` - CRUD operations
- `ActivityLogController.php` - Activity log display

### Models
- `User.php` - User authentication
- `Student.php` - Student records
- `ActivityLog.php` - Activity tracking

### Services
- `ActivityLogService.php` - Centralized logging
- `FileUploadService.php` - Photo management
- `ExportService.php` - Excel/PDF generation

### Views
- `auth/login.blade.php` - Login form
- `dashboard.blade.php` - Statistics dashboard
- `students/index.blade.php` - Student list with search
- `students/create.blade.php` - Create form
- `students/edit.blade.php` - Edit form
- `students/show.blade.php` - Detail view
- `activity-logs/index.blade.php` - Activity log list
- `layouts/app.blade.php` - Main layout template

### Request Validation
- `LoginRequest.php` - Login validation
- `StoreStudentRequest.php` - Create validation
- `UpdateStudentRequest.php` - Update validation

## Key Features

✓ User authentication with session management
✓ Complete CRUD operations for students
✓ Photo upload and management
✓ Search and filter functionality
✓ Excel and PDF export
✓ Activity logging for audit trail
✓ Dashboard with statistics
✓ Responsive Bootstrap UI
✓ Dark/light theme support
✓ Form validation with error display
✓ Pagination for large datasets
✓ CSRF protection
✓ XSS prevention
✓ Comprehensive test coverage

