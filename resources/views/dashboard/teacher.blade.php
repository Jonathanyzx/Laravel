@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Teacher Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Courses</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_courses'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_students'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Attendance Records</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_attendance'] }}</p>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-4">Your Courses</h2>
            
            @if($courses->isEmpty())
                <p class="text-gray-500">You haven't been assigned to any courses yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-xl font-semibold mb-2">{{ $course->name }}</h3>
                            <p class="text-gray-600 mb-2">Code: {{ $course->course_code }}</p>
                            <p class="text-gray-600 mb-2">Credits: {{ $course->credits }}</p>
                            <p class="text-gray-600 mb-2">Duration: {{ $course->duration_months }} months</p>
                            <p class="text-gray-600 mb-2">Fee: ${{ number_format($course->fee, 2) }}</p>
                            <p class="text-gray-600 mb-2">Status: 
                                <span class="px-2 py-1 rounded text-sm {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 