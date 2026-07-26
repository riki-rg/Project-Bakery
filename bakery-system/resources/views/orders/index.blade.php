<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Manajemen Pesanan
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Filter & Search -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block caption text-text-muted mb-2">Cari Pesanan</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Order number, nama, email..."
                        class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block caption text-text-muted mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Status</option>
                        <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="payment_verified" {{ request('status') === 'payment_verified' ? 'selected' : '' }}>Pembayaran Terverifikasi</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="ready_pickup" {{ request('status') === 'ready_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="delivering" {{ request('status') === 'delivering' ? 'selected' : '' }}>Dalam Pengantaran</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block caption text-text-muted mb-2">Dari Tanggal</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                </div>

                <div>
                    <label class="block caption text-text-muted mb-2">Sampai Tanggal</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                </div>

                <!-- Submit -->
                <div class="md:col-span-4">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('orders.index') }}" class="btn btn-outline ml-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="card">
            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-subtle">
                        <tr>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Order Number</th>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Customer</th>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Tanggal</th>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Total</th>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Status</th>
                            <th class="px-4 py-3 text-left body-sm font-semibold text-text-main">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-t border-border hover:bg-surface-subtle transition-colors">
                            <td class="px-4 py-4">
                                <a href="{{ route('orders.show', $order->id) }}" class="body-sm font-bold text-primary hover:text-primary-hover">
                                    {{ $order->order_number }}
                                </a>
                                <div class="caption text-text-muted">
                                    {{ $order->items->count() }} item • {{ ucfirst($order->fulfillment_type) }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="body-sm font-semibold text-text-main">{{ $order->customer->name }}</div>
                                <div class="caption text-text-muted">{{ $order->customer->email }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="body-sm text-text-main">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="caption text-text-muted">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="body-sm font-bold text-text-main tabular-nums">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusClass = match($order->status) {
                                        'pending_payment' => 'badge-warning',
                                        'payment_verified', 'processing', 'ready_pickup', 'delivering' => 'badge-info',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-danger',
                                        default => 'badge-secondary'
                                    };
                                    $statusLabel = match($order->status) {
                                        'pending_payment' => 'Menunggu Pembayaran',
                                        'payment_verified' => 'Pembayaran Terverifikasi',
                                        'processing' => 'Diproses',
                                        'ready_pickup' => 'Siap Diambil',
                                        'delivering' => 'Dalam Pengantaran',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => ucfirst($order->status)
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-4 border-t border-border">
                {{ $orders->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                    <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="body-md text-text-muted">Tidak ada pesanan ditemukan</p>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
