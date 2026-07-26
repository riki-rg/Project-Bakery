<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="heading-lg text-text-main">
                Detail Pesanan
            </h2>
            <a href="{{ route('orders.index') }}" class="btn btn-outline">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="page-container">
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-success-soft border border-success text-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Order Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Order Info Card -->
                <div class="card">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="heading-md text-text-main mb-2">{{ $order->order_number }}</h3>
                            <p class="body-sm text-text-muted">
                                Dibuat: {{ $order->created_at->locale('id')->translatedFormat('d F Y, H:i') }}
                            </p>
                        </div>
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
                        <span class="badge {{ $statusClass }} text-base">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-6 p-4 rounded-lg bg-surface-subtle">
                        <h4 class="body-sm font-bold text-text-main mb-3">Informasi Customer</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="body-sm text-text-main font-semibold">{{ $order->customer->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="body-sm text-text-muted">{{ $order->customer->email }}</span>
                            </div>
                            @if($order->customer->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="body-sm text-text-muted">{{ $order->customer->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recipient & Address -->
                    @if($order->recipient_snapshot)
                    <div class="mb-6 p-4 rounded-lg bg-surface-subtle">
                        <h4 class="body-sm font-bold text-text-main mb-3">Informasi Penerima</h4>
                        <div class="space-y-2">
                            <p class="body-sm text-text-main font-semibold">{{ $order->recipient_snapshot['name'] }}</p>
                            <p class="body-sm text-text-muted">{{ $order->recipient_snapshot['phone'] }}</p>
                            @if($order->fulfillment_type === 'delivery' && $order->address_snapshot)
                            <div class="mt-3 pt-3 border-t border-border">
                                <p class="body-sm text-text-main">{{ $order->address_snapshot['address_line'] }}</p>
                                <p class="body-sm text-text-muted">
                                    {{ $order->address_snapshot['city'] ?? '' }} {{ $order->address_snapshot['postal_code'] ?? '' }}
                                </p>
                                @if(!empty($order->address_snapshot['courier_note']))
                                <p class="body-sm text-text-muted mt-2"><em>Note: {{ $order->address_snapshot['courier_note'] }}</em></p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Items -->
                    <div>
                        <h4 class="body-sm font-bold text-text-main mb-4">Item Pesanan</h4>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-surface-subtle">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-primary-soft flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="body-sm font-semibold text-text-main">{{ $item->product_snapshot['name'] ?? 'Product' }}</p>
                                        <p class="caption text-text-muted">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="body-sm font-bold text-text-main tabular-nums">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="mt-6 pt-6 border-t border-border space-y-2">
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Subtotal</span>
                            <span class="text-text-main tabular-nums">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Diskon</span>
                            <span class="text-success tabular-nums">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->delivery_fee > 0)
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Ongkir</span>
                            <span class="text-text-main tabular-nums">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between body-md font-bold pt-2 border-t border-border">
                            <span class="text-text-main">Total</span>
                            <span class="text-primary tabular-nums">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Actions & History -->
            <div class="space-y-6">
                
                <!-- Update Status Card -->
                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                <div class="card">
                    <h4 class="body-md font-bold text-text-main mb-4">Update Status</h4>
                    <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block caption text-text-muted mb-2">Status Baru</label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary" required>
                                <option value="">Pilih Status</option>
                                @if($order->status === 'pending_payment')
                                <option value="payment_verified">Pembayaran Terverifikasi</option>
                                <option value="cancelled">Batalkan</option>
                                @endif
                                @if($order->status === 'payment_verified')
                                <option value="processing">Mulai Proses</option>
                                <option value="cancelled">Batalkan</option>
                                @endif
                                @if($order->status === 'processing')
                                <option value="ready_pickup">Siap Diambil</option>
                                <option value="delivering">Kirim Sekarang</option>
                                @endif
                                @if($order->status === 'ready_pickup')
                                <option value="completed">Selesai (Sudah Diambil)</option>
                                @endif
                                @if($order->status === 'delivering')
                                <option value="completed">Selesai (Sudah Diterima)</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block caption text-text-muted mb-2">Catatan (opsional)</label>
                            <textarea 
                                name="note" 
                                rows="3" 
                                class="w-full px-4 py-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary"
                                placeholder="Tambahkan catatan untuk perubahan status..."
                            ></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full">
                            Update Status
                        </button>
                    </form>
                </div>
                @endif

                <!-- Status History -->
                <div class="card">
                    <h4 class="body-md font-bold text-text-main mb-4">Riwayat Status</h4>
                    <div class="space-y-4">
                        @foreach($order->statusHistories as $history)
                        <div class="relative pl-6 pb-4 border-l-2 border-border last:border-l-0 last:pb-0">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-primary border-2 border-surface"></div>
                            <div>
                                <p class="body-sm font-semibold text-text-main">
                                    {{ match($history->to_status) {
                                        'pending_payment' => 'Menunggu Pembayaran',
                                        'payment_verified' => 'Pembayaran Terverifikasi',
                                        'processing' => 'Diproses',
                                        'ready_pickup' => 'Siap Diambil',
                                        'delivering' => 'Dalam Pengantaran',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => ucfirst($history->to_status)
                                    } }}
                                </p>
                                <p class="caption text-text-muted mt-1">
                                    {{ $history->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                                </p>
                                @if($history->note)
                                <p class="body-sm text-text-muted mt-2">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Info -->
                @if($order->payment)
                <div class="card">
                    <h4 class="body-md font-bold text-text-main mb-4">Info Pembayaran</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Provider</span>
                            <span class="text-text-main font-semibold uppercase">{{ $order->payment->provider }}</span>
                        </div>
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Status</span>
                            <span class="badge {{ $order->payment->status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between body-sm">
                            <span class="text-text-muted">Jumlah</span>
                            <span class="text-text-main font-bold tabular-nums">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
</x-app-layout>
