<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')
            ->with('employeeProfile')
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'employee_code' => ['required', 'string', 'unique:employee_profiles'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'joined_at' => ['nullable', 'date'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'is_active' => true,
        ]);

        EmployeeProfile::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'phone' => $request->phone,
            'address' => $request->address,
            'joined_at' => $request->joined_at,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee)
    {
        $employee->load('employeeProfile');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $employee->id],
            'employee_code' => ['required', 'string', 'unique:employee_profiles,employee_code,' . $employee->employeeProfile->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'joined_at' => ['nullable', 'date'],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $employee->employeeProfile->update([
            'employee_code' => $request->employee_code,
            'phone' => $request->phone,
            'address' => $request->address,
            'joined_at' => $request->joined_at,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function deactivate(User $employee)
    {
        $employee->update(['is_active' => false]);
        return redirect()->route('employees.index')->with('success', 'Employee deactivated successfully.');
    }

    public function activate(User $employee)
    {
        $employee->update(['is_active' => true]);
        return redirect()->route('employees.index')->with('success', 'Employee activated successfully.');
    }

    public function show(User $employee)
    {
        $employee->load(['employeeProfile', 'attendances' => function($query) {
            $query->orderBy('attendance_date', 'desc')->take(30);
        }]);

        return view('employees.show', compact('employee'));
    }
}
