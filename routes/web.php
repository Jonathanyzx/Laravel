<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->group(function () {
        Route::resource('students', StudentController::class);
        // Admin-only course actions
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::patch('/courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');

        // Admin-only enrollment actions
        Route::resource('enrollments', EnrollmentController::class);
        Route::patch('/enrollments/{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('enrollments.approve');
        Route::patch('/enrollments/{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('enrollments.reject');

        // Admin-only attendance actions
        Route::resource('attendance', AttendanceController::class);
    });

    // Teacher Routes
    Route::middleware([\App\Http\Middleware\CheckRole::class.':teacher'])->group(function () {
        Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');
        Route::get('/my-students', [StudentController::class, 'myStudents'])->name('students.my-students');
        Route::get('/mark-attendance', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/mark-attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    });

    // Student Routes
    Route::middleware([\App\Http\Middleware\CheckRole::class.':student'])->group(function () {
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('enrollments.my-courses');
        Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my-attendance');
    });

    // Shared Routes (accessible by all authenticated users)
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
});

require __DIR__.'/auth.php';
