<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">Promo & Voucher</h2>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-sm rounded-lg border border-border">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-text-main">Kelola Promo & Voucher</h2>
                    <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-text-inverse uppercase tracking-widest hover:bg-primary-hover transition">
                        + Tambah Promo
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-success-soft text-success rounded border border-success/20">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-surface-subtle">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Diskon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Min. Belanja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Kuota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-muted uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-surface divide-y divide-border">
                            @foreach($promotions as $promo)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-sm font-mono bg-primary-soft text-primary-active rounded">{{ $promo->code }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-text-main">{{ $promo->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-text-muted">
                                    @if($promo->type === 'percentage') Persentase
                                    @elseif($promo->type === 'fixed') Tetap
                                    @else Gratis Ongkir
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-text-main">
                                    @if($promo->type === 'percentage')
                                        {{ $promo->value }}%
                                        @if($promo->max_discount)
                                            <span class="text-xs text-text-muted">(max Rp {{ number_format($promo->max_discount) }})</span>
                                        @endif
                                    @elseif($promo->type === 'fixed')
                                        Rp {{ number_format($promo->value) }}
                                    @else
                                        Gratis
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-text-muted">
                                    Rp {{ number_format($promo->min_spend) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-text-muted">
                                    @if($promo->quota)
                                        {{ $promo->used_count }} / {{ $promo->quota }}
                                    @else
                                        ∞
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($promo->is_active && $promo->isValid())
                                        <span class="px-2 py-1 text-xs bg-success-soft text-success rounded border border-success/20">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-surface-subtle text-text-muted rounded border border-border">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('admin.promotions.toggle', $promo) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-info hover:text-info/80 mr-3 font-medium transition">
                                            {{ $promo->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.promotions.edit', $promo) }}" class="text-primary hover:text-primary-hover mr-3 font-medium transition">Edit</a>
                                    <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Hapus promo ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-danger hover:text-danger-hover font-medium transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $promotions->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
