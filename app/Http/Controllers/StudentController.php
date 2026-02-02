<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Services\ActivityLogService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected ActivityLogService $activityLogService;
    protected FileUploadService $fileUploadService;

    public function __construct(ActivityLogService $activityLogService, FileUploadService $fileUploadService)
    {
        $this->activityLogService = $activityLogService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of students with optional search functionality.
     */
    public function index(Request $request)
    {
        try {
            $query = Student::query();

            // Implement search functionality
            if ($request->has('search') && $request->search !== null && $request->search !== '') {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('full_name', 'like', "%{$searchTerm}%")
                        ->orWhere('student_id', 'like', "%{$searchTerm}%")
                        ->orWhere('course', 'like', "%{$searchTerm}%")
                        ->orWhere('year_level', 'like', "%{$searchTerm}%");
                });
            }

            $students = $query->paginate(20);

            return view('students.index', compact('students'));
        } catch (\Exception $e) {
            \Log::error('Error loading student list: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            return view('students.index', ['students' => collect()])
                ->with('error', 'An error occurred while loading the student list. Please try again.');
        }
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Handle photo upload if present
            if ($request->hasFile('photo')) {
                $validatedData['photo_path'] = $this->fileUploadService->uploadPhoto($request->file('photo'));
            }

            // Create the student
            $student = Student::create($validatedData);

            // Log the creation
            $this->activityLogService->logCreate(Auth::user(), $student);

            return redirect()->route('students.show', $student)
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $request->except('photo'),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the student. Please try again.');
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            $validatedData = $request->validated();

            // Track changes for activity log
            $changes = [];
            foreach ($validatedData as $key => $value) {
                if ($key !== 'photo' && $student->{$key} != $value) {
                    $changes[$key] = [
                        'old' => $student->{$key},
                        'new' => $value,
                    ];
                }
            }

            // Handle photo upload if present
            if ($request->hasFile('photo')) {
                $oldPhotoPath = $student->photo_path;
                $validatedData['photo_path'] = $this->fileUploadService->uploadPhoto(
                    $request->file('photo'),
                    $oldPhotoPath
                );
                $changes['photo_path'] = [
                    'old' => $oldPhotoPath,
                    'new' => $validatedData['photo_path'],
                ];
            }

            // Update the student
            $student->update($validatedData);

            // Log the update if there were changes
            if (!empty($changes)) {
                $this->activityLogService->logUpdate(Auth::user(), $student, $changes);
            }

            return redirect()->route('students.show', $student)
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating student: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'student_id' => $student->id,
                'data' => $request->except('photo'),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the student. Please try again.');
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        try {
            // Log the deletion before deleting
            $this->activityLogService->logDelete(Auth::user(), $student);

            // Delete photo if exists
            if ($student->photo_path) {
                $this->fileUploadService->deletePhoto($student->photo_path);
            }

            // Delete the student
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting student: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'student_id' => $student->id,
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the student. Please try again.');
        }
    }
}
