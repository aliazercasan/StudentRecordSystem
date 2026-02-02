<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index()
    {
        try {
            $activityLogs = ActivityLog::with(['user', 'student'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            return view('activity-logs.index', compact('activityLogs'));
        } catch (\Exception $e) {
            \Log::error('Error loading activity logs: ' . $e->getMessage(), [
                'user_id' => \Auth::id(),
            ]);

            return view('activity-logs.index', ['activityLogs' => collect()])
                ->with('error', 'An error occurred while loading activity logs. Please try again.');
        }
    }
}
