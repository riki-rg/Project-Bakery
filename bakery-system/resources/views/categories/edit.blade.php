<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Edit Kategori
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="heading-xl text-text-main mb-2">Edit Kategori</h1>
            <p class="body-md text-text-muted">Perbarui informasi kategori produk</p>
        </div>

        <!-- Form Card -->
        <div class="max-w-form">
            <div class="card">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="form-group mb-6">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $category->name) }}"
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
                        <div class="form-helper">Slug saat ini: <span class="font-semibold">{{ $category->slug }}</span></div>
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
                        >{{ old('description', $category->description) }}</textarea>
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
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-border text-primary focus:ring-primary focus:ring-offset-0"
                            />
                            <span class="ml-3">
                                <span class="body-sm font-semibold text-text-main">Aktifkan kategori</span>
                                <span class="block caption text-text-muted">Kategori aktif dapat digunakan untuk produk</span>
                            </span>
                        </label>
                    </div>

                    <!-- Meta Info -->
                    <div class="p-4 rounded-xl bg-surface-subtle mb-8">
                        <div class="caption text-text-muted space-y-1">
                            <div>Dibuat: {{ $category->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</div>
                            <div>Terakhir diubah: {{ $category->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}</div>
                        </div>
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
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
