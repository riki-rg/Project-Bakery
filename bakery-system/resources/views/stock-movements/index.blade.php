<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Pergerakan Stok
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="heading-xl text-text-main mb-2">Riwayat Pergerakan Stok</h1>
                <p class="body-md text-text-muted">Pantau semua transaksi stok masuk, keluar, dan penyesuaian</p>
            </div>
            <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Catat Stok</span>
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

        <!-- Filter Bar -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('stock-movements.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <select name="product_id" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                    @endforeach
                </select>
                
                <select name="type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                </select>
                
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal" class="form-input">
                
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal" class="form-input">
                
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-secondary flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Filter</span>
                    </button>
                    @if(request('product_id') || request('type') || request('date_from') || request('date_to'))
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-ghost">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stock Movements Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Sebelum</th>
                        <th>Sesudah</th>
                        <th>Oleh</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr>
                        <td class="caption text-text-muted whitespace-nowrap">
                            {{ $movement->occurred_at->locale('id')->translatedFormat('d M Y') }}<br>
                            {{ $movement->occurred_at->format('H:i') }}
                        </td>
                        <td>
                            <div class="font-semibold text-text-main">{{ $movement->product->name }}</div>
                            <div class="caption text-text-muted">{{ $movement->product->category->name }}</div>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $movement->type == 'in' ? 'badge-success' : ($movement->type == 'out' ? 'badge-danger' : 'badge-info') }}">
                                @if($movement->type == 'in')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                    </svg>
                                    MASUK
                                @elseif($movement->type == 'out')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                    </svg>
                                    KELUAR
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    ADJUST
                                @endif
                            </span>
                        </td>
                        <td class="font-bold tabular-nums {{ $movement->type == 'in' ? 'text-success' : ($movement->type == 'out' ? 'text-danger' : 'text-info') }}">
                            {{ $movement->type == 'out' ? '-' : '+' }}{{ $movement->quantity }}
                        </td>
                        <td class="tabular-nums text-text-muted">{{ $movement->stock_before }}</td>
                        <td class="tabular-nums font-semibold text-text-main">{{ $movement->stock_after }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-primary-soft flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-bold text-xs">{{ substr($movement->user->name, 0, 1) }}</span>
                                </div>
                                <span class="body-sm text-text-main">{{ $movement->user->name }}</span>
                            </div>
                        </td>
                        <td class="caption text-text-muted">{{ $movement->note ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                                <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="heading-md text-text-main mb-2">Belum ada pergerakan stok</p>
                            <p class="body-md text-text-muted mb-6">Mulai catat stok masuk atau keluar</p>
                            <a href="{{ route('stock-movements.create') }}" class="btn btn-primary inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Catat Stok</span>
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($movements->hasPages())
        <div class="mt-6">
            {{ $movements->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
