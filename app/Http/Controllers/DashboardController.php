<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with student statistics.
     */
    public function index()
    {
        try {
            // Calculate total student count
            $totalStudents = Student::count();
            
            // Calculate students grouped by year_level
            $studentsByYearLevel = Student::selectRaw('year_level, COUNT(*) as count')
                ->groupBy('year_level')
                ->orderBy('year_level')
                ->pluck('count', 'year_level')
                ->toArray();
            
            // Ensure all year levels (1-6) are represented
            $yearLevelCounts = [];
            for ($i = 1; $i <= 6; $i++) {
                $yearLevelCounts[$i] = $studentsByYearLevel[$i] ?? 0;
            }
            
            // Calculate students grouped by course
            $studentsByCourse = Student::selectRaw('course, COUNT(*) as count')
                ->groupBy('course')
                ->orderBy('count', 'desc')
                ->get()
                ->pluck('count', 'course')
                ->toArray();
            
            return view('dashboard', [
                'totalStudents' => $totalStudents,
                'yearLevelCounts' => $yearLevelCounts,
                'courseCounts' => $studentsByCourse,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading dashboard: ' . $e->getMessage(), [
                'user_id' => \Auth::id(),
            ]);

            return view('dashboard', [
                'totalStudents' => 0,
                'yearLevelCounts' => array_fill(1, 6, 0),
                'courseCounts' => [],
            ])->with('error', 'An error occurred while loading dashboard statistics. Please try again.');
        }
    }
}
