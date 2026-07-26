<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Karyawan
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="heading-xl text-text-main mb-2">Kelola Karyawan</h1>
                <p class="body-md text-text-muted">Atur data karyawan toko roti Anda</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Karyawan</span>
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="body-sm">{{ session('success') }}</div>
        </div>
        @endif

        <!-- Employees Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($employees as $employee)
            <div class="card">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xl">{{ substr($employee->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="heading-sm text-text-main mb-1 truncate">{{ $employee->name }}</h3>
                        <p class="caption text-text-muted truncate">{{ $employee->email }}</p>
                        @if($employee->employeeProfile)
                        <p class="caption text-text-muted mt-1">
                            <span class="font-semibold">Kode:</span> {{ $employee->employeeProfile->employee_code }}
                        </p>
                        @endif
                    </div>
                    <span class="badge {{ $employee->is_active ? 'badge-success' : 'badge-neutral' }}">
                        {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                @if($employee->employeeProfile)
                <div class="p-4 rounded-xl bg-surface-subtle mb-4 space-y-2">
                    @if($employee->employeeProfile->phone)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="caption text-text-muted">{{ $employee->employeeProfile->phone }}</span>
                    </div>
                    @endif
                    @if($employee->employeeProfile->joined_at)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="caption text-text-muted">Bergabung: {{ $employee->employeeProfile->joined_at->locale('id')->translatedFormat('M Y') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('employees.show', $employee) }}" class="flex-1 btn btn-secondary">
                        Detail
                    </a>
                    <a href="{{ route('employees.edit', $employee) }}" class="flex-1 btn btn-ghost">
                        Edit
                    </a>
                    @if($employee->is_active)
                    <form action="{{ route('employees.deactivate', $employee) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost p-2 text-danger" onclick="return confirm('Yakin ingin menonaktifkan karyawan ini?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('employees.activate', $employee) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost p-2 text-success">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                    <svg class="w-10 h-10 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="heading-md text-text-main mb-2">Belum ada karyawan</p>
                <p class="body-md text-text-muted mb-6">Tambahkan karyawan pertama Anda</p>
                <a href="{{ route('employees.create') }}" class="btn btn-primary inline-flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Karyawan</span>
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="mt-8">
            {{ $employees->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
