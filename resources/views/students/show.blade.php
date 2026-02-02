@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="container mt-4">
    <h1>Student Details</h1>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Student ID</th>
                            <td>{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>{{ $student->course }}</td>
                        </tr>
                        <tr>
                            <th>Year Level</th>
                            <td>{{ $student->year_level }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number</th>
                            <td>{{ $student->contact_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $student->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4 text-center">
                    @if($student->photo_path)
                        <img src="{{ asset('storage/' . $student->photo_path) }}" 
                             alt="Student Photo" 
                             class="img-fluid rounded" 
                             style="max-width: 250px;">
                    @else
                        <img src="{{ asset('images/default-avatar.svg') }}" 
                             alt="Default Placeholder" 
                             class="img-fluid rounded" 
                             style="max-width: 250px;">
                        <p class="text-muted mt-2">No photo available</p>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">Edit</a>
                <form method="POST" action="{{ route('students.destroy', $student) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this student?');" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
