<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Tambah Kategori
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">Tambah Kategori Baru</h1>
            <p class="body-md text-text-muted">Buat kategori baru untuk mengelompokkan produk</p>
        </div>

        <!-- Form Card -->
        <div class="max-w-form">
            <div class="card">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group mb-6">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="form-input w-full @error('name') border-danger @enderror"
                            placeholder="Contoh: Roti Manis, Pastry, Kue Kering"
                        />
                        @error('name')
                        <div class="form-error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                        <div class="form-helper">Nama kategori akan otomatis diubah menjadi slug URL</div>
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-6">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="form-textarea w-full @error('description') border-danger @enderror"
                            placeholder="Deskripsi singkat tentang kategori ini (opsional)"
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
                                <span class="body-sm font-semibold text-text-main">Aktifkan kategori</span>
                                <span class="block caption text-text-muted">Kategori aktif dapat digunakan untuk produk</span>
                            </span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-border">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Kategori</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
