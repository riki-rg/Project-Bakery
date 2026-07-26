<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Tambah Produk
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">Tambah Produk Baru</h1>
            <p class="body-md text-text-muted">Tambahkan produk roti baru ke katalog Anda</p>
        </div>

        <!-- Form Card -->
        <div class="max-w-form">
            <div class="card">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Category -->
                    <div class="form-group mb-6">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" required class="form-select w-full @error('category_id') border-danger @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="form-group mb-6">
                        <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="form-input w-full @error('name') border-danger @enderror"
                            placeholder="Contoh: Croissant Butter Premium"
                        />
                        @error('name')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-6">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="form-textarea w-full @error('description') border-danger @enderror"
                            placeholder="Deskripsi produk (opsional)"
                        >{{ old('description') }}</textarea>
                        @error('description')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="form-group mb-6">
                        <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 body-sm text-text-muted font-semibold">Rp</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price') }}"
                                step="0.01" 
                                min="0" 
                                required
                                class="form-input w-full pl-12 @error('price') border-danger @enderror"
                                placeholder="0"
                            />
                        </div>
                        @error('price')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group mb-6">
                        <label for="image" class="form-label">Gambar Produk</label>
                        <div class="border-2 border-dashed border-border rounded-xl p-6 hover:border-primary transition-colors">
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(event)"
                            />
                            <label for="image" class="cursor-pointer flex flex-col items-center">
                                <div id="preview-container" class="hidden w-full mb-4">
                                    <img id="preview" class="w-full h-48 object-cover rounded-lg" />
                                </div>
                                <svg class="w-12 h-12 text-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="body-sm font-semibold text-text-main mb-1">Klik untuk upload gambar</span>
                                <span class="caption text-text-muted">JPEG, PNG, JPG, GIF (Max: 2MB)</span>
                            </label>
                        </div>
                        @error('image')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Low Stock Threshold -->
                    <div class="form-group mb-6">
                        <label for="low_stock_threshold" class="form-label">Batas Stok Rendah <span class="text-danger">*</span></label>
                        <input 
                            type="number" 
                            id="low_stock_threshold" 
                            name="low_stock_threshold" 
                            value="{{ old('low_stock_threshold', 10) }}"
                            min="0" 
                            required
                            class="form-input w-full @error('low_stock_threshold') border-danger @enderror"
                        />
                        @error('low_stock_threshold')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                        <div class="form-helper">Sistem akan memberi peringatan saat stok mencapai batas ini</div>
                    </div>

                    <!-- Status -->
                    <div class="form-group mb-8">
                        <label class="inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-border text-primary focus:ring-primary focus:ring-offset-0"
                            />
                            <span class="ml-3">
                                <span class="body-sm font-semibold text-text-main">Aktifkan produk</span>
                                <span class="block caption text-text-muted">Produk aktif akan ditampilkan di katalog</span>
                            </span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-border">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
