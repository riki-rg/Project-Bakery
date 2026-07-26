<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Absensi Saya
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">Absensi Saya</h1>
            <p class="body-md text-text-muted">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="body-sm">{{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="body-sm">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Clock Panel -->
        <div class="card mb-8">
            <div class="text-center">
                <div class="mb-6">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-2xl bg-primary flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div id="current-time" class="display-md text-text-main mb-2 tabular-nums">--:--:--</div>
                    <div class="body-lg text-text-muted">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
                </div>

                @php
                    $todayAttendance = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereDate('attendance_date', today())
                        ->first();
                @endphp

                @if($todayAttendance)
                    <!-- Today's Status -->
                    <div class="p-6 rounded-xl bg-surface-subtle mb-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="caption text-text-muted mb-2">Check In</div>
                                <div class="heading-md text-success">
                                    {{ $todayAttendance->check_in_at ? $todayAttendance->check_in_at->format('H:i') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="caption text-text-muted mb-2">Check Out</div>
                                <div class="heading-md {{ $todayAttendance->check_out_at ? 'text-primary' : 'text-text-muted' }}">
                                    {{ $todayAttendance->check_out_at ? $todayAttendance->check_out_at->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-center">
                    @if(!$todayAttendance || !$todayAttendance->check_in_at)
                    <form method="POST" action="{{ route('attendance.check-in') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Check In</span>
                        </button>
                    </form>
                    @endif

                    @if($todayAttendance && $todayAttendance->check_in_at && !$todayAttendance->check_out_at)
                    <form method="POST" action="{{ route('attendance.check-out') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Check Out</span>
                        </button>
                    </form>
                    @endif

                    @if($todayAttendance && $todayAttendance->check_in_at && $todayAttendance->check_out_at)
                    <div class="badge badge-success text-lg px-6 py-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Absensi Hari Ini Lengkap</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="card">
            <h3 class="heading-md text-text-main mb-6">Riwayat Absensi</h3>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Catatan Koreksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td class="whitespace-nowrap">
                                {{ $attendance->attendance_date->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <td class="tabular-nums font-semibold text-success">
                                {{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-' }}
                            </td>
                            <td class="tabular-nums font-semibold text-primary">
                                {{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : '-' }}
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="caption text-text-muted">
                                {{ $attendance->correction_note ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                                    <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="body-md text-text-muted">Belum ada riwayat absensi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
            @endif
        </div>

    </div>

    <script>
        // Real-time clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
