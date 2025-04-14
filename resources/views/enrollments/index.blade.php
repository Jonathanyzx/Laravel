@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Enrollment Management</h2>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Enrollment
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
            @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
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
                                    <td>{{ $enrollment->course->name }}</td>
                                    <td>{{ $enrollment->course->teacher->name }}</td>
                                    <td>{{ $enrollment->enrollment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $enrollment->status === 'active' ? 'success' : 
                                            ($enrollment->status === 'completed' ? 'info' : 
                                            ($enrollment->status === 'dropped' ? 'danger' : 'warning')) 
                                        }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('enrollments.show', $enrollment) }}" 
                                               class="btn btn-sm btn-info text-white" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('enrollments.edit', $enrollment) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   title="Edit Enrollment">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('enrollments.destroy', $enrollment) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this enrollment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete Enrollment">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('enrollments.update-status', $enrollment) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-{{ $enrollment->status === 'active' ? 'secondary' : 'success' }}"
                                                            title="{{ $enrollment->status === 'active' ? 'Mark as Inactive' : 'Mark as Active' }}">
                                                        <i class="fas fa-{{ $enrollment->status === 'active' ? 'ban' : 'check' }}"></i>
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
                    {{ $enrollments->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No enrollments found.</p>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create First Enrollment
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 