<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'min:3', 'regex:/.*@.*/'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.min' => 'Email minimal 3 karakter.',
            'email.regex' => 'Format email tidak valid. Email harus mengandung karakter @.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Provides specific error messages:
     * - User not found: "Pengguna tidak ditemukan"
     * - Wrong password: "Email / password salah"
     * - 3+ failed attempts: flashes 'show_admin_help' to trigger modal
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = $this->input('email');
        $user = User::where('email', $email)->first();

        // Check if user exists in DB
        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            $this->trackFailedAttempt();

            throw ValidationException::withMessages([
                'email' => 'Pengguna tidak ditemukan.',
            ]);
        }

        // User exists, attempt auth (wrong password case)
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->trackFailedAttempt();

            throw ValidationException::withMessages([
                'email' => 'Email / password salah.',
            ]);
        }

        // Success — clear counters
        RateLimiter::clear($this->throttleKey());
        session()->forget('login_failed_attempts');
    }

    /**
     * Track consecutive failed login attempts in session.
     * After 3+ failures, flash a flag to show admin contact modal.
     */
    protected function trackFailedAttempt(): void
    {
        $attempts = session()->get('login_failed_attempts', 0) + 1;
        session()->put('login_failed_attempts', $attempts);

        if ($attempts > 0 && $attempts % 3 === 0) {
            session()->flash('show_admin_help', true);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
