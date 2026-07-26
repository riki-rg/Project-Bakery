<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Dashboard
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">
                @php
                    $hour = date('G');
                    $greeting = $hour < 12 ? 'Selamat pagi' : ($hour < 18 ? 'Selamat siang' : 'Selamat malam');
                @endphp
                {{ $greeting }}, {{ auth()->user()->name }}
            </h1>
            <p class="body-lg text-text-muted">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Products Card -->
            <div class="card-stat">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-primary-soft flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="caption text-text-muted mb-1">Total Produk Aktif</div>
                    <div class="stat text-primary">{{ $totalProducts }}</div>
                </div>
            </div>

            <!-- Total Employees Card -->
            <div class="card-stat">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-info-soft flex items-center justify-center">
                        <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="caption text-text-muted mb-1">Karyawan Aktif</div>
                    <div class="stat text-info">{{ $totalEmployees }}</div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="card-stat">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-warning-soft flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="caption text-text-muted mb-1">Stok Rendah/Habis</div>
                    <div class="stat text-warning">{{ $lowStockProducts->count() }}</div>
                </div>
            </div>

            <!-- Today Attendance Card -->
            <div class="card-stat">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 rounded-xl bg-success-soft flex items-center justify-center">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="caption text-text-muted mb-1">Kehadiran Hari Ini</div>
                    <div class="stat text-success">{{ $todayAttendance->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Stock Movement Analytics Chart -->
        <div class="card mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="heading-md text-text-main">Pergerakan Stok (7 Hari Terakhir)</h3>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-success"></div>
                        <span class="caption text-text-muted">Stok Masuk</span>
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <div class="w-3 h-3 rounded-full bg-danger"></div>
                        <span class="caption text-text-muted">Stok Keluar</span>
                    </div>
                </div>
            </div>
            
            <div class="relative" style="height: 300px;">
                <canvas id="stockMovementChart"></canvas>
            </div>
        </div>

        <!-- Stock Trends Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Stock IN Trend -->
            <div class="card">
                <div class="flex items-center justify-between mb-2">
                    <div class="caption text-text-muted">Stok Masuk (7 hari)</div>
                    <div class="w-8 h-8 rounded-lg bg-success-soft flex items-center justify-center">
                        <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="heading-lg text-text-main tabular-nums">{{ $stockTrends['stockIn']['value'] }}</div>
                    <div class="flex items-center gap-1 {{ $stockTrends['stockIn']['trend'] == 'up' ? 'text-success' : 'text-danger' }}">
                        @if($stockTrends['stockIn']['trend'] == 'up')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <span class="body-sm font-semibold">{{ abs($stockTrends['stockIn']['change']) }}%</span>
                    </div>
                </div>
                <div class="caption text-text-muted mt-1">vs minggu lalu</div>
            </div>

            <!-- Stock OUT Trend -->
            <div class="card">
                <div class="flex items-center justify-between mb-2">
                    <div class="caption text-text-muted">Stok Keluar (7 hari)</div>
                    <div class="w-8 h-8 rounded-lg bg-danger-soft flex items-center justify-center">
                        <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="heading-lg text-text-main tabular-nums">{{ $stockTrends['stockOut']['value'] }}</div>
                    <div class="flex items-center gap-1 {{ $stockTrends['stockOut']['trend'] == 'up' ? 'text-danger' : 'text-success' }}">
                        @if($stockTrends['stockOut']['trend'] == 'up')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <span class="body-sm font-semibold">{{ abs($stockTrends['stockOut']['change']) }}%</span>
                    </div>
                </div>
                <div class="caption text-text-muted mt-1">vs minggu lalu</div>
            </div>

            <!-- Adjustments Trend -->
            <div class="card">
                <div class="flex items-center justify-between mb-2">
                    <div class="caption text-text-muted">Penyesuaian (7 hari)</div>
                    <div class="w-8 h-8 rounded-lg bg-info-soft flex items-center justify-center">
                        <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="heading-lg text-text-main tabular-nums">{{ $stockTrends['adjustments']['value'] }}</div>
                    <div class="flex items-center gap-1 text-info">
                        @if($stockTrends['adjustments']['change'] == 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                            </svg>
                        @elseif($stockTrends['adjustments']['trend'] == 'up')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <span class="body-sm font-semibold">{{ $stockTrends['adjustments']['change'] == 0 ? 'sama' : abs($stockTrends['adjustments']['change']) . '%' }}</span>
                    </div>
                </div>
                <div class="caption text-text-muted mt-1">vs minggu lalu</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: 2/3 width -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Low Stock Products Alert -->
                @if($lowStockProducts->count() > 0)
                <div class="card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="heading-md text-text-main">Produk Perlu Perhatian</h3>
                        <a href="{{ route('products.index') }}" class="body-sm text-primary hover:text-primary-hover font-semibold">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($lowStockProducts->take(5) as $product)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-subtle hover:bg-warning-soft/30 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-warning-soft flex items-center justify-center">
                                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="body-sm font-semibold text-text-main">{{ $product->name }}</div>
                                    <div class="caption text-text-muted">{{ $product->category->name }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="body-sm font-bold tabular-nums {{ $product->current_stock == 0 ? 'text-danger' : 'text-warning' }}">
                                    {{ $product->current_stock }}
                                </div>
                                <div class="caption text-text-muted">dari {{ $product->low_stock_threshold }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Top Moving Products -->
                <div class="card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="heading-md text-text-main">Produk Paling Aktif (7 Hari)</h3>
                        <span class="caption text-text-muted">Berdasarkan pergerakan stok</span>
                    </div>
                    
                    @if($topMovingProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($topMovingProducts as $index => $item)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-subtle hover:bg-primary-soft/30 transition-colors">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary text-white font-bold body-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="body-sm font-semibold text-text-main">{{ $item->product->name }}</div>
                                <div class="caption text-text-muted">{{ $item->product->category->name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="body-sm font-bold text-primary tabular-nums">{{ $item->movement_count }}</div>
                                <div class="caption text-text-muted">pergerakan</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-subtle flex items-center justify-center">
                            <svg class="w-6 h-6 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <p class="body-sm text-text-muted">Belum ada data pergerakan</p>
                    </div>
                    @endif
                </div>

                <!-- Slow Moving Products -->
                <div class="card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="heading-md text-text-main">Produk Kurang Aktif (7 Hari)</h3>
                        <span class="caption text-text-muted">≤ 3 pergerakan</span>
                    </div>
                    
                    @if($slowMovingProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($slowMovingProducts as $item)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-subtle hover:bg-warning-soft/30 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-warning-soft flex items-center justify-center">
                                <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="body-sm font-semibold text-text-main">{{ $item['product']->name }}</div>
                                <div class="caption text-text-muted">{{ $item['product']->category->name }} • Stok: {{ $item['product']->current_stock }}</div>
                            </div>
                            <div class="text-right">
                                <div class="body-sm font-bold text-warning tabular-nums">{{ $item['movement_count'] }}</div>
                                <div class="caption text-text-muted">pergerakan</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-success-soft flex items-center justify-center">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="body-sm text-text-muted">Semua produk aktif bergerak!</p>
                    </div>
                    @endif
                </div>

                <!-- Recent Stock Movements -->
                <div class="card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="heading-md text-text-main">Pergerakan Stok Terbaru</h3>
                        <a href="{{ route('stock-movements.index') }}" class="body-sm text-primary hover:text-primary-hover font-semibold">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($recentStockMovements->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentStockMovements as $movement)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-subtle">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                    {{ $movement->type == 'in' ? 'bg-success-soft' : ($movement->type == 'out' ? 'bg-danger-soft' : 'bg-info-soft') }}">
                                    @if($movement->type == 'in')
                                        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        </svg>
                                    @elseif($movement->type == 'out')
                                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="body-sm font-semibold text-text-main">{{ $movement->product->name }}</div>
                                    <div class="caption text-text-muted">
                                        {{ $movement->user->name }} • {{ $movement->occurred_at->locale('id')->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge 
                                    {{ $movement->type == 'in' ? 'badge-success' : ($movement->type == 'out' ? 'badge-danger' : 'badge-info') }}">
                                    {{ strtoupper($movement->type) }} {{ $movement->quantity }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                            <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <p class="body-md text-text-muted">Belum ada pergerakan stok</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: 1/3 width -->
            <div class="space-y-8">
                
                <!-- Today's Attendance (Owner Only) -->
                @if(auth()->user()->isOwner())
                <div class="card">
                    <h3 class="heading-md text-text-main mb-6">Kehadiran Hari Ini</h3>
                    
                    @if($todayAttendance->count() > 0)
                    <div class="space-y-3">
                        @foreach($todayAttendance as $attendance)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-subtle">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr($attendance->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="body-sm font-semibold text-text-main truncate">{{ $attendance->user->name }}</div>
                                <div class="caption text-text-muted">
                                    {{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-' }}
                                    @if($attendance->check_out_at)
                                        - {{ $attendance->check_out_at->format('H:i') }}
                                    @endif
                                </div>
                            </div>
                            <span class="badge badge-success">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-subtle flex items-center justify-center">
                            <svg class="w-6 h-6 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="body-sm text-text-muted">Belum ada kehadiran</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="card">
                    <h3 class="heading-md text-text-main mb-6">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if(auth()->user()->isOwner())
                        <a href="{{ route('products.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-primary-soft hover:bg-primary hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-primary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="body-sm font-semibold text-primary group-hover:text-white">Tambah Produk</span>
                        </a>
                        @endif
                        
                        <a href="{{ route('stock-movements.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-accent-soft hover:bg-accent hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-accent group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="body-sm font-semibold text-accent group-hover:text-white">Catat Stok</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('stockMovementChart');
            
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($stockChartData['labels']),
                        datasets: [
                            {
                                label: 'Stok Masuk',
                                data: @json($stockChartData['stockIn']),
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(34, 197, 94)',
                                borderWidth: 2,
                                borderRadius: 8,
                            },
                            {
                                label: 'Stok Keluar',
                                data: @json($stockChartData['stockOut']),
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: 'rgb(239, 68, 68)',
                                borderWidth: 2,
                                borderRadius: 8,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                                titleColor: 'rgba(255, 255, 255, 1)',
                                bodyColor: 'rgba(255, 255, 255, 0.9)',
                                padding: 12,
                                borderColor: 'rgba(75, 85, 99, 0.3)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold',
                                    family: 'Inter, sans-serif'
                                },
                                bodyFont: {
                                    size: 13,
                                    family: 'Inter, sans-serif'
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.parsed.y + ' items';
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(107, 114, 128, 1)',
                                    font: {
                                        size: 12,
                                        family: 'Inter, sans-serif'
                                    }
                                },
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.5)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(107, 114, 128, 1)',
                                    font: {
                                        size: 12,
                                        family: 'Inter, sans-serif'
                                    }
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
