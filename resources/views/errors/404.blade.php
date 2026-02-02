@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <h1 class="display-1 text-muted">404</h1>
                    <h2 class="mb-4">Page Not Found</h2>
                    <p class="lead mb-4">
                        Sorry, the page you are looking for could not be found.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('students.index') }}" class="btn btn-primary">
                            <i class="bi bi-list"></i> Go to Student List
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-house"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
