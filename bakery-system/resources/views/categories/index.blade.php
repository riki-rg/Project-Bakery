<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Kategori Produk
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="heading-xl text-text-main mb-2">Kelola Kategori</h1>
                <p class="body-md text-text-muted">Atur kategori produk toko roti Anda</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Kategori</span>
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

        <!-- Categories Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
                        <th>Terakhir Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <div class="font-semibold text-text-main">{{ $category->name }}</div>
                            @if($category->description)
                            <div class="caption text-text-muted mt-1">{{ Str::limit($category->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="caption text-text-muted">{{ $category->slug }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $category->products_count }} produk
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-neutral' }}">
                                {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="caption text-text-muted">{{ $category->updated_at->locale('id')->diffForHumans() }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="btn-ghost p-2 rounded-lg hover:bg-primary-soft">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost p-2 rounded-lg hover:bg-danger-soft text-danger" onclick="return confirm('Yakin ingin menonaktifkan kategori ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                                <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <p class="body-md text-text-muted mb-4">Belum ada kategori produk</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Tambah Kategori Pertama</span>
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
