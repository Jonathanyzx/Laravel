@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col">
            <h4 class="text-gray-800">Dashboard Overview</h4>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Total Students Card -->
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-users fa-fw"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Students</h6>
                            <h4 class="card-title mb-0">{{ $stats['total_students'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses Card -->
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-book fa-fw"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Courses</h6>
                            <h4 class="card-title mb-0">{{ $stats['total_courses'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Enrollments Card -->
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-user-graduate fa-fw"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Enrollments</h6>
                            <h4 class="card-title mb-0">{{ $stats['total_enrollments'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Attendance Card -->
        <div class="col-sm-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-clipboard-check fa-fw"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Attendance</h6>
                            <h4 class="card-title mb-0">{{ $stats['total_attendance'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-sm-6 col-md-3">
                            <a href="{{ route('students.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-user-plus"></i> Add Student
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <a href="{{ route('courses.create') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-plus"></i> Add Course
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <a href="{{ route('enrollments.create') }}" class="btn btn-warning w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-user-graduate"></i> New Enrollment
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <a href="{{ route('attendance.create') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-clipboard-check"></i> Mark Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4">
        <!-- Recent Students -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Recent Students</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentStudents->isEmpty())
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <p class="mb-0">No students yet</p>
                            </div>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentStudents as $student)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if($student->profile_photo)
                                                <img src="{{ asset('storage/' . $student->profile_photo) }}" 
                                                     class="rounded-circle" 
                                                     width="32" height="32" 
                                                     alt="{{ $student->name }}">
                                            @else
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="mb-0 fw-medium">{{ $student->name }}</p>
                                            <small class="text-muted">{{ $student->student_id }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Recent Courses</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentCourses->isEmpty())
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <p class="mb-0">No courses yet</p>
                            </div>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentCourses as $course)
                                <div class="list-group-item">
                                    <p class="mb-1 fw-medium">{{ $course->name }}</p>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        {{ $course->teacher->name }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Enrollments -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Recent Enrollments</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentEnrollments->isEmpty())
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <p class="mb-0">No enrollments yet</p>
                            </div>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentEnrollments as $enrollment)
                                <div class="list-group-item">
                                    <p class="mb-1 fw-medium">{{ $enrollment->student->name }}</p>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-book me-1"></i>
                                        {{ $enrollment->course->name }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $enrollment->enrollment_date->format('M d, Y') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item {
    transition: background-color 0.2s ease-in-out;
}

.list-group-item:hover {
    background-color: rgba(0, 0, 0, 0.01);
}

.btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.card-header {
    padding: 0.75rem 1rem;
}

.card-body {
    padding: 1rem;
}

@media (max-width: 768px) {
    .stats-icon {
        width: 35px;
        height: 35px;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .btn {
        font-size: 0.8125rem;
    }
}
</style>
@endpush 