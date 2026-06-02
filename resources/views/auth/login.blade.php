@section('auth-title', 'Login')
@section('page-title', 'Login — LLU Portal')

<x-guest-layout>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if ($errors->any())
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:10px 14px;margin-bottom:14px;font-size:12px;color:#1e40af;">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix:</strong>
            <ul style="margin:4px 0 0 14px;padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <label class="field-label">Email</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-envelope"></i></span>
            <div class="vdivider"></div>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="Enter your email here"
                   autocomplete="off" required />
        </div>

        <!-- PASSWORD -->
        <label class="field-label">Password</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-lock"></i></span>
            <div class="vdivider"></div>
            <input id="pw" type="password" name="password"
                   placeholder="Enter your password here"
                   autocomplete="current-password" required />
            <button type="button" class="eye-btn" onclick="togglePw('pw','pw-icon')">
                <i id="pw-icon" class="fas fa-eye"></i>
            </button>
        </div>

        <!-- Remember + Forgot -->
        <div class="remember-row">
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password</a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i>Login
        </button>

        <!-- Sign Up -->
        <p class="bottom-text">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </p>

    </form>

</x-guest-layout>

@push('scripts')
<script>
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') {
        f.type = 'text';
        i.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        f.type = 'password';
        i.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush