<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $enrollments = Enrollment::with(['student.user', 'course.teacher'])
            ->when($user->isTeacher(), function ($query) {
                return $query->whereHas('course', function ($q) {
                    $q->where('teacher_id', Auth::id());
                });
            })
            ->when($user->isStudent(), function ($query) {
                return $query->whereHas('student.user', function ($q) {
                    $q->where('id', Auth::id());
                });
            })
            ->latest()
            ->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $courses = Course::where('is_active', true)->with('teacher')->get();
        
        return view('enrollments.create', compact('students', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,pending,completed,dropped'
        ]);

        // Check if student is already enrolled in the course
        $exists = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Student is already enrolled in this course.');
        }

        $enrollment = Enrollment::create($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.user', 'course.teacher']);
        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        $students = Student::with('user')->get();
        $courses = Course::where('is_active', true)->with('teacher')->get();
        
        return view('enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,pending,completed,dropped'
        ]);

        // Check if student is already enrolled in the course (excluding current enrollment)
        $exists = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->where('id', '!=', $enrollment->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Student is already enrolled in this course.');
        }

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully.');
    }

    public function myCourses()
    {
        $enrollments = Enrollment::with(['course.teacher'])
            ->whereHas('student.user', function ($query) {
                $query->where('id', Auth::id());
            })
            ->where('status', 'active')
            ->get();

        return view('enrollments.my-courses', compact('enrollments'));
    }

    /**
     * Update the enrollment status.
     */
    public function updateStatus(Enrollment $enrollment)
    {
        $newStatus = $enrollment->status === 'active' ? 'inactive' : 'active';
        $enrollment->update(['status' => $newStatus]);

        return redirect()->route('enrollments.index')
            ->with('success', "Enrollment status updated to " . ucfirst($newStatus));
    }
}
