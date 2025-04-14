@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Course Management</h2>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Course
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Teacher</th>
                                <th>Credits</th>
                                <th>Duration</th>
                                <th>Fee</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{ $course->course_code }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->teacher->name ?? 'Not Assigned' }}</td>
                                    <td>{{ $course->credits }}</td>
                                    <td>{{ $course->duration_months }} months</td>
                                    <td>${{ number_format($course->fee, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $course->is_active ? 'success' : 'danger' }}">
                                            {{ $course->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('courses.show', $course) }}" 
                                               class="btn btn-sm btn-info text-white" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('courses.edit', $course) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   title="Edit Course">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('courses.destroy', $course) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete Course">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('courses.toggle-status', $course) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-{{ $course->is_active ? 'secondary' : 'success' }}"
                                                            title="{{ $course->is_active ? 'Deactivate' : 'Activate' }} Course">
                                                        <i class="fas fa-{{ $course->is_active ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No courses available.</p>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Course
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 