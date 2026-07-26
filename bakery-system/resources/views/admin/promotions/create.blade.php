<x-app-layout>
    <x-slot name="header">
        <h2 class="heading-lg text-text-main">Tambah Promo</h2>
    </x-slot>

<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Tambah Promo Baru</h2>

                <form action="{{ route('admin.promotions.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Promo <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 uppercase" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Promo <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe <span class="text-red-500">*</span></label>
                                <select name="type" id="type-select" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                                    <option value="percentage">Persentase (%)</option>
                                    <option value="fixed">Nominal Tetap (Rp)</option>
                                    <option value="shipping">Gratis Ongkir</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nilai <span class="text-red-500">*</span></label>
                                <input type="number" name="value" value="{{ old('value') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maks. Diskon (untuk %)</label>
                                <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min. Belanja (Rp)</label>
                                <input type="number" name="min_spend" value="{{ old('min_spend', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kuota</label>
                                <input type="number" name="quota" value="{{ old('quota') }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Kosongkan = unlimited">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Penggunaan/Pelanggan</label>
                                <input type="number" name="usage_limit_per_customer" value="{{ old('usage_limit_per_customer', 1) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berlaku Dari</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Berlaku Sampai</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="hidden" name="first_time_customer_only" value="0">
                                <input type="checkbox" name="first_time_customer_only" value="1" {{ old('first_time_customer_only') ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Hanya pelanggan baru</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition">Simpan</button>
                            <a href="{{ route('admin.promotions.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
