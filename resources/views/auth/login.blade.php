<x-guest-layout>
    <div class="glass-card rounded-3xl shadow-2xl shadow-[rgb(var(--color-primary))_/_0.1] overflow-hidden" x-data="loginForm()">

        {{-- Header --}}
        <div class="px-8 pt-10 pb-6 text-center">
            {{-- Logo / Icon --}}
            <div
                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[rgb(var(--color-primary))] to-[rgb(var(--color-primary))] flex items-center justify-center mx-auto mb-5 shadow-lg shadow-[rgb(var(--color-primary))_/_0.3]">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-zinc-900 tracking-tight">Lab Management</h1>
            <p class="text-sm font-medium text-zinc-500 mt-1.5">Masuk ke sistem manajemen laboratorium</p>
        </div>

        @if (session('status'))
            <div class="mx-8 mb-4 p-3.5 ui-primary-soft border border-[rgb(var(--color-primary)_/_0.2)] rounded-xl">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[rgb(var(--color-primary)_/_0.15)] flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-[rgb(var(--color-primary))]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-[rgb(var(--color-primary))]">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="px-8 pb-8" @submit="onSubmit">
            @csrf

            {{-- Global Error (login failure) --}}
            @if ($errors->has('email') && (old('email') || old('password')))
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200/80 rounded-xl">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            @foreach ($errors->get('email') as $message)
                                <p class="text-xs font-semibold text-rose-700">{{ $message }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Email --}}
            <div class="mb-5">
                <label for="email" class="block text-xs font-bold text-zinc-700 mb-1.5">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input id="email" name="email" type="text" value="{{ old('email') }}" x-model="email"
                        placeholder="nama@email.com" class="block w-full h-12 pl-11 pr-4 text-sm font-medium text-zinc-800 bg-zinc-50 border rounded-xl transition-all duration-200 placeholder:text-zinc-400
                            {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)]' }}
                            focus:outline-none focus:bg-white" autocomplete="username" autofocus>
                </div>
                {{-- Client-side email validation hint --}}
                <p x-show="emailError" x-text="emailError" x-cloak class="mt-1.5 text-xs font-semibold text-rose-600">
                </p>
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label for="password" class="block text-xs font-bold text-zinc-700 mb-1.5">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <input id="password" name="password" x-model="password" :type="showPassword ? 'text' : 'password'"
                        placeholder="Masukkan password" class="block w-full h-12 pl-11 pr-12 text-sm font-medium text-zinc-800 bg-zinc-50 border rounded-xl transition-all duration-200 placeholder:text-zinc-400
                            {{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-zinc-200 focus:border-[rgb(var(--color-primary))] focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)]' }}
                            focus:outline-none focus:bg-white" autocomplete="current-password">

                    {{-- Toggle password visibility --}}
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-zinc-400 hover:text-zinc-600 transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                {{-- Client-side password validation hint --}}
                <p x-show="passwordError" x-text="passwordError" x-cloak
                    class="mt-1.5 text-xs font-semibold text-rose-600"></p>
                @if ($errors->has('password'))
                    @foreach ($errors->get('password') as $message)
                        <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @endforeach
                @endif
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-zinc-300 text-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] transition-colors">
                    <span
                        class="ms-2 text-xs font-semibold text-zinc-500 group-hover:text-zinc-700 transition-colors">Ingat
                        saya</span>
                </label>

                <a class="text-xs font-semibold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary))] transition-colors"
                    href="https://wa.me/6289827283625" target="_blank" rel="noopener noreferrer">
                    Lupa password?
                </a>
            </div>

            {{-- Submit Button --}}
            <button type="submit" :disabled="isSubmitting"
                class="w-full h-12 flex items-center justify-center gap-2 bg-gradient-to-r from-[rgb(var(--color-primary))] to-[rgb(var(--color-primary))] hover:from-[rgb(var(--color-primary))] hover:to-[rgb(var(--color-primary))] text-white text-sm font-bold rounded-xl shadow-lg shadow-[rgb(var(--color-primary))_/_0.3] hover:shadow-[rgb(var(--color-primary))_/_0.4] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span x-text="isSubmitting ? 'Memproses...' : 'Masuk'"></span>
            </button>
        </form>

        {{-- Footer --}}
        <div class="px-8 py-4 border-t border-zinc-100 bg-zinc-50/50 text-center">
            <p class="text-[11px] font-medium text-zinc-400">
                &copy; {{ date('Y') }} Lab Management System — UIN Raden Intan Lampung
            </p>
        </div>
    </div>

    @if (session('show_admin_help'))
        <div x-data="{ showModal: true }" x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="glass-card rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-8 text-center"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" @click.outside="showModal = false">

                <div
                    class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5 ring-1 ring-amber-100">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>

                <h3 class="text-lg font-extrabold text-zinc-900 mb-2">Kesulitan Masuk?</h3>
                <p class="text-sm font-medium text-zinc-500 mb-6 leading-relaxed">
                    Anda telah gagal masuk beberapa kali. Jika Anda lupa akun, silakan hubungi Administrator untuk
                    mendapatkan bantuan.
                </p>

                <div class="flex flex-col gap-3">
                    <a href="https://wa.me/6289827283625" target="_blank" rel="noopener noreferrer"
                        class="w-full h-12 flex items-center justify-center gap-2 ui-primary hover:opacity-90 text-white text-sm font-bold rounded-xl shadow-lg shadow-[rgb(var(--color-primary))_/_0.2] transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Hubungi Administrator
                    </a>
                    <button @click="showModal = false" type="button"
                        class="w-full h-11 text-sm font-semibold text-zinc-600 hover:text-zinc-800 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition-colors">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        function loginForm() {
            return {
                email: '{{ old("email") }}',
                password: '',
                showPassword: false,
                isSubmitting: false,
                emailError: '',
                passwordError: '',

                onSubmit(e) {
                    this.emailError = '';
                    this.passwordError = '';
                    let valid = true;

                    // Client-side email validation
                    if (!this.email || this.email.trim().length < 3) {
                        this.emailError = 'Email minimal 3 karakter.';
                        valid = false;
                    } else if (!this.email.includes('@')) {
                        this.emailError = 'Format email tidak valid. Email harus mengandung karakter @.';
                        valid = false;
                    }

                    // Client-side password validation
                    if (!this.password || this.password.length < 6) {
                        this.passwordError = 'Password minimal 6 karakter.';
                        valid = false;
                    }

                    if (!valid) {
                        e.preventDefault();
                        return;
                    }

                    this.isSubmitting = true;
                }
            }
        }
    </script>
</x-guest-layout>