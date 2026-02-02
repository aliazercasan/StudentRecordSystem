@extends('layouts.app')

@section('title', 'Student List')
@section('page-title', 'Student Records')

@section('content')
<div class="content-area">
    <!-- Header with Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="font-weight: 700;">Student Records</h2>
            <p class="text-muted mb-0">Manage and view all student information</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New Student
        </a>
    </div>

    <!-- Search Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}">
                <div class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text" style="background: white; border-right: none;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control" 
                                style="border-left: none;"
                                placeholder="Search by name, student ID, course, or year level" 
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>
            @if(request('search'))
                <div class="mt-3">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Clear Search
                    </a>
                    <span class="text-muted ms-2">Showing results for: <strong>"{{ request('search') }}"</strong></span>
                </div>
            @endif
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="card">
        @if($students->isEmpty())
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3 mb-0">No student records found.</p>
                @if(request('search'))
                    <p class="text-muted">Try adjusting your search criteria.</p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Student ID</th>
                            <th style="width: 30%;">Full Name</th>
                            <th style="width: 25%;">Course</th>
                            <th style="width: 15%;">Year Level</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 12px; padding: 6px 10px;">
                                        {{ $student->student_id }}
                                    </span>
                                </td>
                                <td style="font-weight: 500;">{{ $student->full_name }}</td>
                                <td>{{ $student->course }}</td>
                                <td>
                                    <span class="badge bg-secondary">Year {{ $student->year_level }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                        </div>
                        <div>
                            {{ $students->links('pagination::simple-bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .input-group-text {
        border-radius: 8px 0 0 8px;
    }
    
    .btn-group .btn {
        border-radius: 6px;
        margin-right: 4px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Custom pagination styling */
    .pagination {
        gap: 4px;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
    }

    /* Fix pagination arrow size */
    .page-link svg {
        width: 16px;
        height: 16px;
    }
</style>
@endsection
