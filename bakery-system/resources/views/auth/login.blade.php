<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="heading-lg text-text-main mb-2">Selamat datang kembali</h1>
        <p class="body-md text-text-muted">Masuk ke panel operasional toko roti Anda</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="body-sm">{{ session('status') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="body-sm">{{ session('error') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="form-input w-full @error('email') border-danger @enderror"
                placeholder="nama@contoh.com"
            />
            @error('email')
                <div class="form-error">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="form-input w-full @error('password') border-danger @enderror"
                placeholder="Masukkan kata sandi"
            />
            @error('password')
                <div class="form-error">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 rounded border-border text-primary focus:ring-primary focus:ring-offset-0"
                />
                <span class="ml-2 body-sm text-text-main">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="body-sm text-primary hover:text-primary-hover transition-colors duration-fast">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-lg w-full">
            <span>Masuk ke Dashboard</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>
    </form>

    <!-- Helper Text -->
    <div class="mt-8 pt-6 border-t border-border">
        <p class="caption text-center text-text-muted">
            Sistem manajemen toko roti untuk operasional internal
        </p>
    </div>
</x-guest-layout>
