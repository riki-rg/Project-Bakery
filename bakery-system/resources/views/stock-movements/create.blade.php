<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Catat Stok
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">Catat Pergerakan Stok</h1>
            <p class="body-md text-text-muted">Catat stok masuk, keluar, atau penyesuaian produk</p>
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

        <!-- Form Card -->
        <div class="max-w-form">
            <div class="card">
                <form method="POST" action="{{ route('stock-movements.store') }}">
                    @csrf

                    <!-- Product Selector -->
                    <div class="form-group mb-6">
                        <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" required class="form-select w-full @error('product_id') border-danger @enderror" onchange="updateStockInfo(this)">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->current_stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Stok: {{ $product->current_stock }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                        <div id="current-stock" class="form-helper hidden">
                            Stok saat ini: <span class="font-bold tabular-nums" id="stock-value">0</span>
                        </div>
                    </div>

                    <!-- Type Selector -->
                    <div class="form-group mb-6">
                        <label for="type" class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex flex-col items-center p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-success {{ old('type') == 'in' ? 'border-success bg-success-soft' : 'border-border' }}">
                                <input type="radio" name="type" value="in" required class="sr-only" {{ old('type') == 'in' ? 'checked' : '' }}>
                                <svg class="w-8 h-8 mb-2 {{ old('type') == 'in' ? 'text-success' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                <span class="body-sm font-semibold {{ old('type') == 'in' ? 'text-success' : 'text-text-main' }}">Stok Masuk</span>
                            </label>
                            
                            <label class="relative flex flex-col items-center p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-danger {{ old('type') == 'out' ? 'border-danger bg-danger-soft' : 'border-border' }}">
                                <input type="radio" name="type" value="out" required class="sr-only" {{ old('type') == 'out' ? 'checked' : '' }}>
                                <svg class="w-8 h-8 mb-2 {{ old('type') == 'out' ? 'text-danger' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                </svg>
                                <span class="body-sm font-semibold {{ old('type') == 'out' ? 'text-danger' : 'text-text-main' }}">Stok Keluar</span>
                            </label>
                            
                            <label class="relative flex flex-col items-center p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-info {{ old('type') == 'adjustment' ? 'border-info bg-info-soft' : 'border-border' }}">
                                <input type="radio" name="type" value="adjustment" required class="sr-only" {{ old('type') == 'adjustment' ? 'checked' : '' }}>
                                <svg class="w-8 h-8 mb-2 {{ old('type') == 'adjustment' ? 'text-info' : 'text-text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="body-sm font-semibold {{ old('type') == 'adjustment' ? 'text-info' : 'text-text-main' }}">Penyesuaian</span>
                            </label>
                        </div>
                        @error('type')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="form-group mb-6">
                        <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            value="{{ old('quantity') }}"
                            min="1" 
                            required
                            class="form-input w-full @error('quantity') border-danger @enderror"
                            placeholder="Masukkan jumlah"
                        />
                        @error('quantity')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Note -->
                    <div class="form-group mb-8">
                        <label for="note" class="form-label">Keterangan</label>
                        <textarea 
                            id="note" 
                            name="note" 
                            rows="3"
                            class="form-textarea w-full @error('note') border-danger @enderror"
                            placeholder="Tambahkan keterangan (opsional)"
                        >{{ old('note') }}</textarea>
                        @error('note')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-border">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Transaksi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function updateStockInfo(select) {
            const option = select.options[select.selectedIndex];
            const stock = option.dataset.stock;
            const stockDisplay = document.getElementById('current-stock');
            const stockValue = document.getElementById('stock-value');
            
            if (stock !== undefined) {
                stockValue.textContent = stock;
                stockDisplay.classList.remove('hidden');
            } else {
                stockDisplay.classList.add('hidden');
            }
        }

        // Radio button styling
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset all labels
                document.querySelectorAll('label').forEach(label => {
                    if (label.querySelector('input[type="radio"]')) {
                        label.classList.remove('border-success', 'bg-success-soft', 'border-danger', 'bg-danger-soft', 'border-info', 'bg-info-soft');
                        label.classList.add('border-border');
                    }
                });
                
                // Highlight selected
                const label = this.closest('label');
                const value = this.value;
                if (value === 'in') {
                    label.classList.add('border-success', 'bg-success-soft');
                    label.classList.remove('border-border');
                } else if (value === 'out') {
                    label.classList.add('border-danger', 'bg-danger-soft');
                    label.classList.remove('border-border');
                } else if (value === 'adjustment') {
                    label.classList.add('border-info', 'bg-info-soft');
                    label.classList.remove('border-border');
                }
            });
        });
    </script>
</x-app-layout>
