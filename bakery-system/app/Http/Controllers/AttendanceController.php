<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(20);
        $employees = User::where('role', 'employee')->where('is_active', true)->get();

        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function checkIn()
    {
        $user = auth()->user();
        $today = today();

        // Check if already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'You have already checked in today.']);
        }

        Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $today,
            'check_in_at' => now(),
            'status' => 'present',
        ]);

        return back()->with('success', 'Check-in recorded successfully.');
    }

    public function checkOut()
    {
        $user = auth()->user();
        $today = today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return back()->withErrors(['error' => 'You need to check in first.']);
        }

        if ($attendance->check_out_at) {
            return back()->withErrors(['error' => 'You have already checked out today.']);
        }

        $attendance->update([
            'check_out_at' => now(),
        ]);

        return back()->with('success', 'Check-out recorded successfully.');
    }

    public function myAttendance()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->orderBy('attendance_date', 'desc')
            ->paginate(20);

        return view('attendances.my-attendance', compact('attendances'));
    }

    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date', 'after:check_in_at'],
            'status' => ['required', 'in:present,late,incomplete,absent'],
            'correction_note' => ['required', 'string'],
        ]);

        $attendance->update([
            'check_in_at' => $request->check_in_at,
            'check_out_at' => $request->check_out_at,
            'status' => $request->status,
            'correction_note' => $request->correction_note,
            'corrected_by' => auth()->id(),
        ]);

        return redirect()->route('attendances.index')->with('success', 'Attendance corrected successfully.');
    }
}
