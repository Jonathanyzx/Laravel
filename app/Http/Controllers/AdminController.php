<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_courses' => Course::where('is_active', true)->count(),
            'total_enrollments' => Enrollment::where('status', 'active')->count(),
            'total_attendance' => 0, // You'll need to implement this based on your attendance model
        ];

        // Get pending approvals
        $pendingApprovals = User::whereHas('student', function($query) {
                $query->where('status', 'pending');
            })
            ->orWhereHas('teacher', function($query) {
                $query->where('status', 'pending');
            })
            ->with(['student', 'teacher'])
            ->get();

        // Get recent data
        $recentStudents = Student::with('user')
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        $recentCourses = Course::with('teacher')
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'pendingApprovals',
            'recentStudents',
            'recentCourses',
            'recentEnrollments'
        ));
    }

    public function approveUser(User $user)
    {
        if ($user->isStudent()) {
            $user->student->update(['status' => 'active']);
        } elseif ($user->isTeacher()) {
            $user->teacher->update(['status' => 'active']);
        }

        return back()->with('success', ucfirst($user->role) . ' approved successfully.');
    }

    public function rejectUser(User $user)
    {
        if ($user->isStudent()) {
            $user->student->update(['status' => 'inactive']);
        } elseif ($user->isTeacher()) {
            $user->teacher->update(['status' => 'inactive']);
        }

        return back()->with('success', ucfirst($user->role) . ' rejected successfully.');
    }
} 