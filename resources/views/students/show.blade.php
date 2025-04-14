@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Student Details</h1>
    <div>
        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Student
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($student->profile_photo)
                    <img src="{{ asset('storage/' . $student->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                @else
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px;">
                        <i class="fas fa-user fa-5x"></i>
                    </div>
                @endif
                <h3>{{ $student->name }}</h3>
                <p class="text-muted">{{ $student->student_id }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $student->email }}</p>
                        <p><strong>Phone:</strong> {{ $student->phone }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date of Birth:</strong> {{ $student->date_of_birth->format('F d, Y') }}</p>
                        <p><strong>Age:</strong> {{ $student->date_of_birth->age }} years</p>
                    </div>
                </div>
                <p><strong>Address:</strong> {{ $student->address }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Enrolled Courses</h5>
            </div>
            <div class="card-body">
                @if($student->courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->courses as $course)
                                    <tr>
                                        <td>{{ $course->course_code }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->pivot->enrollment_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $course->pivot->status === 'active' ? 'success' : ($course->pivot->status === 'completed' ? 'info' : 'danger') }}">
                                                {{ ucfirst($course->pivot->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $course->pivot->grade ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No courses enrolled yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 