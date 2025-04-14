<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        // No middleware here since we handle permissions in routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $courses = Course::with('teacher')
            ->when($user->isTeacher(), function ($query) use ($user) {
                return $query->where('teacher_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('courses.index', compact('courses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['teacher', 'enrollments.student']);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('courses.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'credits' => 'required|integer|min:1',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'teacher_id' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $course = Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'credits' => 'required|integer|min:1',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'teacher_id' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if ($course->enrollments()->exists()) {
            return back()->with('error', 'Cannot delete course with active enrollments.');
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function toggleStatus(Course $course)
    {
        $course->update(['is_active' => !$course->is_active]);

        return back()->with('success', 'Course status updated successfully.');
    }
}
