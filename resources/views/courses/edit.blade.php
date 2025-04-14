@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">Edit Course</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('courses.update', $course) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" 
                                id="course_code" name="course_code" 
                                value="{{ old('course_code', $course->course_code) }}" 
                                required>
                            @error('course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Course Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" 
                                value="{{ old('name', $course->name) }}" 
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" 
                                rows="3" required>{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="credits" class="form-label">Credits</label>
                                    <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                                        id="credits" name="credits" 
                                        value="{{ old('credits', $course->credits) }}" 
                                        required min="1">
                                    @error('credits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="duration_months" class="form-label">Duration (Months)</label>
                                    <input type="number" class="form-control @error('duration_months') is-invalid @enderror" 
                                        id="duration_months" name="duration_months" 
                                        value="{{ old('duration_months', $course->duration_months) }}" 
                                        required min="1">
                                    @error('duration_months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fee" class="form-label">Course Fee</label>
                                    <input type="number" step="0.01" class="form-control @error('fee') is-invalid @enderror" 
                                        id="fee" name="fee" 
                                        value="{{ old('fee', $course->fee) }}" 
                                        required min="0">
                                    @error('fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                id="teacher_id" name="teacher_id" required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                        {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                    id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active Course</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 