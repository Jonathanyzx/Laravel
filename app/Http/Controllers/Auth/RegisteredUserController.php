<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,student,teacher'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create corresponding record based on role
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'date_of_birth' => $request->date_of_birth ?? null,
                'gender' => $request->gender ?? 'other',
                'status' => 'pending', // Pending admin approval
            ]);
        } elseif ($request->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'teacher_id' => 'TCH' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'qualification' => $request->qualification ?? null,
                'specialization' => $request->specialization ?? null,
                'status' => 'pending', // Pending admin approval
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
