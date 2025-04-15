<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    private function adminDashboard(): View
    {
        $stats = [
            'total_students' => Student::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_attendance' => Attendance::count(),
        ];

        $recentStudents = Student::latest()->take(5)->get();
        $recentCourses = Course::with('teacher')->latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['student', 'course'])->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recentStudents', 'recentCourses', 'recentEnrollments'));
    }


    //dashboard of the teachers
    private function teacherDashboard(): View
    {
        /** @var User $teacher */
        $teacher = Auth::user();
        
        $courses = Course::where('teacher_id', $teacher->id)->get();
        $total_students = Enrollment::whereIn('course_id', $courses->pluck('id'))->count();
        $total_attendance = Attendance::whereIn('course_id', $courses->pluck('id'))->count();

        $stats = [
            'total_courses' => $courses->count(),
            'total_students' => $total_students,
            'total_attendance' => $total_attendance,
        ];

        return view('dashboard.teacher', compact('stats', 'courses'));
    }

    private function studentDashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get the student record associated with the user
        $student = $user->student;
        
        if (!$student) {
            abort(404, 'Student profile not found');
        }
        
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['course', 'course.teacher'])
            ->get();
            
        $total_courses = $enrollments->count();
        $total_attendance = Attendance::where('student_id', $student->id)->count();

        $stats = [
            'total_courses' => $total_courses,
            'total_attendance' => $total_attendance,
        ];

        return view('dashboard.student', compact('stats', 'enrollments', 'student'));
    }
} 
