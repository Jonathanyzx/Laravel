<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Enrolled Courses Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Enrolled Courses</h2>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_courses'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Record Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Attendance Record</h2>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_attendance'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Enrolled Courses -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">My Enrolled Courses</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($enrollments->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($enrollments as $enrollment)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $enrollment->course->name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $enrollment->course->description }}</p>
                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Teacher: {{ $enrollment->course->teacher->name }}</span>
                                            <a href="{{ route('courses.show', $enrollment->course) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">You are not enrolled in any courses yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('enrollments.my-courses') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                        View My Courses
                    </a>
                    <a href="{{ route('attendance.my-attendance') }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600">
                        View Attendance
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 