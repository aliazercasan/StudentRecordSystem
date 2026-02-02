@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container mt-4">
    <h1>Edit Student</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                           id="student_id" name="student_id" 
                           value="{{ old('student_id', $student->student_id) }}" required>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                           id="full_name" name="full_name" 
                           value="{{ old('full_name', $student->full_name) }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <input type="text" class="form-control @error('course') is-invalid @enderror" 
                           id="course" name="course" 
                           value="{{ old('course', $student->course) }}" required>
                    @error('course')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="year_level" class="form-label">Year Level</label>
                    <select class="form-control @error('year_level') is-invalid @enderror" 
                            id="year_level" name="year_level" required>
                        <option value="">Select Year Level</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" 
                                {{ old('year_level', $student->year_level) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('year_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                           id="contact_number" name="contact_number" 
                           value="{{ old('contact_number', $student->contact_number) }}">
                    @error('contact_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    @if($student->photo_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $student->photo_path) }}" 
                                 alt="Current Photo" 
                                 style="max-width: 150px;" 
                                 class="img-thumbnail">
                            <p class="text-muted small">Current photo</p>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                           id="photo" name="photo" accept="image/jpeg,image/png,image/gif">
                    <small class="form-text text-muted">Accepted formats: JPEG, PNG, GIF. Max size: 2MB. Leave empty to keep current photo.</small>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.show', $student) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
