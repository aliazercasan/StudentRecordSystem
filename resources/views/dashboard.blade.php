@extends('layouts.app')

@section('title', 'Dashboard - Student Record System')
@section('page-title', 'Dashboard')

@section('content')
<div class="content-area">
    <div class="row g-4 mb-4">
        <!-- Total Students Card -->
        <div class="col-md-4">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 14px;">Total Students</p>
                            <h2 class="mb-0" style="font-size: 42px; font-weight: 700;">{{ $totalStudents }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-people-fill" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <p class="mb-0" style="opacity: 0.8; font-size: 13px;">
                        <i class="bi bi-arrow-up"></i> Enrolled students
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 0.5px;">Quick Actions</h6>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('students.create') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; transition: transform 0.3s;">
                                    <i class="bi bi-person-plus-fill" style="font-size: 28px;"></i>
                                    <div class="mt-2" style="font-size: 13px; font-weight: 500;">Add Student</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('students.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                    <i class="bi bi-list-ul" style="font-size: 28px;"></i>
                                    <div class="mt-2" style="font-size: 13px; font-weight: 500;">View All</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('students.index') }}?search=" class="text-decoration-none">
                                <div class="text-center p-3 rounded" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                                    <i class="bi bi-search" style="font-size: 28px;"></i>
                                    <div class="mt-2" style="font-size: 13px; font-weight: 500;">Search</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('activity-logs.index') }}" class="text-decoration-none">
                                <div class="text-center p-3 rounded" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                                    <i class="bi bi-clock-history" style="font-size: 28px;"></i>
                                    <div class="mt-2" style="font-size: 13px; font-weight: 500;">Activity</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Students by Year Level Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bar-chart-fill me-2"></i>Students by Year Level
                </div>
                <div class="card-body">
                    @if(count($yearLevelCounts) > 0)
                        <div class="row g-3">
                            @foreach($yearLevelCounts as $yearLevel => $count)
                                <div class="col-6">
                                    <a href="{{ route('students.index', ['search' => 'Year ' . $yearLevel]) }}" class="text-decoration-none">
                                        <div class="p-3 rounded year-level-card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #667eea; transition: all 0.3s ease;">
                                            <div class="text-muted small mb-1">Year {{ $yearLevel }}</div>
                                            <div class="h4 mb-0" style="color: #667eea; font-weight: 700;">{{ $count }}</div>
                                            <div class="small text-muted">students</div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No data available</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Students by Course Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-mortarboard-fill me-2"></i>Students by Course
                </div>
                <div class="card-body">
                    @if(count($courseCounts) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($courseCounts as $course => $count)
                                <a href="{{ route('students.index', ['search' => $course]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 border-0 py-2 course-item">
                                    <span style="font-weight: 500;">{{ $course }}</span>
                                    <span class="badge rounded-pill" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 6px 12px;">{{ $count }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No courses available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover .text-center {
        transform: scale(1.05);
    }

    /* Course item hover effect */
    .course-item {
        transition: all 0.3s ease;
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
    }

    .course-item:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
        padding-left: 10px !important;
        transform: translateX(5px);
    }

    body.dark-mode .course-item {
        color: #e0e0e0;
    }

    body.dark-mode .course-item:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.2) 0%, transparent 100%);
        color: #667eea;
    }

    /* Year level card hover effect */
    .year-level-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .year-level-card:hover .text-muted,
    .year-level-card:hover .h4 {
        color: white !important;
    }

    /* Fix dark mode text visibility */
    body.dark-mode .card-body span {
        color: #e0e0e0;
    }

    body.dark-mode .text-muted {
        color: #a0a0a0 !important;
    }

    body.dark-mode small {
        color: #a0a0a0;
    }

    body.dark-mode .year-level-card {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    body.dark-mode .year-level-card:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>
@endsection
