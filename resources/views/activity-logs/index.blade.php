@extends('layouts.app')

@section('title', 'Activity Logs - Student Record System')
@section('page-title', 'Activity Logs')

@section('content')
<div class="content-area">
    <div class="mb-4">
        <h2 class="mb-1" style="font-weight: 700;">Activity Logs</h2>
        <p class="text-muted mb-0">Track all system activities and changes</p>
    </div>
    
    <div class="card">
        @if($activityLogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Action Type</th>
                            <th style="width: 18%;">Timestamp</th>
                            <th style="width: 20%;">Administrator</th>
                            <th style="width: 15%;">Student ID</th>
                            <th style="width: 35%;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activityLogs as $log)
                            <tr>
                                <td>
                                    @if($log->action_type === 'create')
                                        <span class="badge" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                            <i class="bi bi-plus-circle me-1"></i>Create
                                        </span>
                                    @elseif($log->action_type === 'update')
                                        <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                            <i class="bi bi-pencil me-1"></i>Update
                                        </span>
                                    @elseif($log->action_type === 'delete')
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('M d, Y H:i:s') }}
                                    </small>
                                </td>
                                <td style="font-weight: 500;">
                                    <i class="bi bi-person-circle me-1"></i>{{ $log->user->name }}
                                </td>
                                <td>
                                    @if($log->student)
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ $log->student->student_id }}
                                        </span>
                                    @elseif(isset($log->record_details['student_id']))
                                        <span class="badge bg-secondary">
                                            {{ $log->record_details['student_id'] }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->action_type === 'create')
                                        <small>Created student: <strong>{{ $log->record_details['full_name'] ?? 'N/A' }}</strong></small>
                                    @elseif($log->action_type === 'update')
                                        <small>Updated fields: <strong>{{ implode(', ', array_keys($log->changed_fields ?? [])) }}</strong></small>
                                    @elseif($log->action_type === 'delete')
                                        <small>Deleted student: <strong>{{ $log->record_details['full_name'] ?? 'N/A' }}</strong></small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($activityLogs->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Showing {{ $activityLogs->firstItem() }} to {{ $activityLogs->lastItem() }} of {{ $activityLogs->total() }} logs
                        </div>
                        <div>
                            {{ $activityLogs->links('pagination::simple-bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3 mb-0">No activity logs found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
