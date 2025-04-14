@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Course Details</h4>
                    @if(Auth::user()->isAdmin())
                        <div>
                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to delete this course?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Basic Information</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Course Code</dt>
                                <dd class="col-sm-8">{{ $course->course_code }}</dd>

                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8">{{ $course->name }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $course->is_active ? 'success' : 'danger' }}">
                                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Course Details</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Credits</dt>
                                <dd class="col-sm-8">{{ $course->credits }}</dd>

                                <dt class="col-sm-4">Duration</dt>
                                <dd class="col-sm-8">{{ $course->duration_months }} months</dd>

                                <dt class="col-sm-4">Fee</dt>
                                <dd class="col-sm-8">${{ number_format($course->fee, 2) }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Description</h5>
                        <p class="mb-0">{{ $course->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Teacher Information</h5>
                        @if($course->teacher)
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($course->teacher->profile_photo)
                                        <img src="{{ asset('storage/' . $course->teacher->profile_photo) }}" 
                                            alt="Teacher Photo" 
                                            class="rounded-circle" 
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" 
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-user fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $course->teacher->name }}</h6>
                                    <p class="text-muted mb-0">{{ $course->teacher->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0">No teacher assigned</p>
                        @endif
                    </div>

                    @if($course->enrollments->isNotEmpty())
                        <div>
                            <h5 class="text-muted mb-3">Enrolled Students ({{ $course->enrollments->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Enrollment Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->enrollments as $enrollment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($enrollment->student->user->profile_photo)
                                                            <img src="{{ asset('storage/' . $enrollment->student->user->profile_photo) }}" 
                                                                alt="Student Photo" 
                                                                class="rounded-circle me-2" 
                                                                style="width: 30px; height: 30px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-2" 
                                                                style="width: 30px; height: 30px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        @endif
                                                        {{ $enrollment->student->user->name }}
                                                    </div>
                                                </td>
                                                <td>{{ $enrollment->enrollment_date->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : 
                                                        ($enrollment->status === 'completed' ? 'info' : 
                                                        ($enrollment->status === 'dropped' ? 'danger' : 'warning')) }}">
                                                        {{ ucfirst($enrollment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No students enrolled yet.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Courses
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('courses.toggle-status', $course) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $course->is_active ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $course->is_active ? 'ban' : 'check' }}"></i>
                                    {{ $course->is_active ? 'Deactivate Course' : 'Activate Course' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 