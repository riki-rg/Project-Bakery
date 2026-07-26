<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">
            Produk
        </h2>
    </x-slot>

    <div class="page-container">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="heading-xl text-text-main mb-2">Kelola Produk</h1>
                <p class="body-md text-text-muted">Atur katalog produk toko roti Anda</p>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Produk</span>
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

        <!-- Filter Bar -->
        <div class="card mb-6">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari produk..." 
                        class="form-input w-full"
                    />
                </div>
                
                <select name="category_id" class="form-select md:w-64">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Filter</span>
                </button>
                
                @if(request('search') || request('category_id'))
                <a href="{{ route('products.index') }}" class="btn btn-ghost">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
            <div class="card-product group">
                <!-- Product Image -->
                <div class="aspect-[4/3] bg-surface-subtle overflow-hidden">
                    @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-base">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="p-5">
                    <div class="mb-3">
                        <h3 class="heading-sm text-text-main mb-1">{{ $product->name }}</h3>
                        <p class="caption text-text-muted">{{ $product->category->name }}</p>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="body-lg font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="caption text-text-muted">Stok: <span class="font-semibold tabular-nums">{{ $product->current_stock }}</span></div>
                        </div>
                        <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-neutral' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    @if($product->isLowStock())
                    <div class="mb-4">
                        <span class="badge badge-warning">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Stok Rendah
                        </span>
                    </div>
                    @endif
                    
                    <div class="flex gap-2">
                        <a href="{{ route('products.show', $product) }}" class="flex-1 btn btn-secondary">
                            Detail
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="flex-1 btn btn-primary">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-xl bg-surface-subtle flex items-center justify-center">
                    <svg class="w-10 h-10 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="heading-md text-text-main mb-2">Belum ada produk</p>
                <p class="body-md text-text-muted mb-6">Mulai tambahkan produk pertama Anda</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary inline-flex">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Produk</span>
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
