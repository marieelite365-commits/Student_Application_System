@section('auth-title', 'Create Account')
@section('page-title', 'Register — LLU Portal')

<x-guest-layout>

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

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- FULL NAME -->
        <label class="field-label">Full Name</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-user"></i></span>
            <div class="vdivider"></div>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Enter your full name"
                   autocomplete="name" autofocus required />
        </div>

        <!-- EMAIL -->
        <label class="field-label">Email Address</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-envelope"></i></span>
            <div class="vdivider"></div>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="Enter your email address"
                   autocomplete="username" required />
        </div>

        <!-- PASSWORD -->
        <label class="field-label">Password</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-lock"></i></span>
            <div class="vdivider"></div>
            <input id="pw" type="password" name="password"
                   placeholder="Create a password (min. 8 chars)"
                   autocomplete="new-password" required />
            <button type="button" class="eye-btn" onclick="togglePw('pw','pw-icon')">
                <i id="pw-icon" class="fas fa-eye"></i>
            </button>
        </div>

        <!-- Password strength bar -->
        <div style="margin-top:-10px;margin-bottom:14px;">
            <div style="display:flex;gap:4px;margin-bottom:4px;">
                <div id="s1" style="height:3px;flex:1;border-radius:99px;background:#e2e8f0;transition:background 0.3s;"></div>
                <div id="s2" style="height:3px;flex:1;border-radius:99px;background:#e2e8f0;transition:background 0.3s;"></div>
                <div id="s3" style="height:3px;flex:1;border-radius:99px;background:#e2e8f0;transition:background 0.3s;"></div>
                <div id="s4" style="height:3px;flex:1;border-radius:99px;background:#e2e8f0;transition:background 0.3s;"></div>
            </div>
            <p id="str-label" style="font-size:11px;color:#94a3b8;"></p>
        </div>

        <!-- CONFIRM PASSWORD -->
        <label class="field-label">Confirm Password</label>
        <div class="input-wrap">
            <span class="icon"><i class="fas fa-lock"></i></span>
            <div class="vdivider"></div>
            <input id="pw2" type="password" name="password_confirmation"
                   placeholder="Re-enter your password"
                   autocomplete="new-password" required />
            <button type="button" class="eye-btn" onclick="togglePw('pw2','pw2-icon')">
                <i id="pw2-icon" class="fas fa-eye"></i>
            </button>
        </div>
        <p id="match-msg" style="font-size:11px;margin-top:-10px;margin-bottom:14px;display:none;"></p>

        <!-- Register Button -->
        <button type="submit" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i>Create Account
        </button>

        <!-- Back to Login -->
        <p class="bottom-text">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </p>

    </form>

</x-guest-layout>

@push('scripts')
<script>
// Toggle password visibility
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') {
        f.type = 'text'; i.classList.replace('fa-eye','fa-eye-slash');
    } else {
        f.type = 'password'; i.classList.replace('fa-eye-slash','fa-eye');
    }
}

// Password strength meter
const pwField = document.getElementById('pw');
const bars    = [1,2,3,4].map(n => document.getElementById('s'+n));
const label   = document.getElementById('str-label');
const colors  = ['#ef4444','#f97316','#eab308','#1e3a8a'];
const labels  = ['Weak','Fair','Good','Strong ✓'];

pwField.addEventListener('input', () => {
    const v = pwField.value;
    let score = 0;
    if (v.length >= 8)           score++;
    if (/[A-Z]/.test(v))         score++;
    if (/[0-9]/.test(v))         score++;
    if (/[^A-Za-z0-9]/.test(v))  score++;
    bars.forEach((b,i) => {
        b.style.background = i < score ? colors[score-1] : '#e2e8f0';
    });
    label.textContent = v.length ? labels[score-1] || '' : '';
    label.style.color = v.length ? colors[score-1] : '#94a3b8';
});

// Password match
const pw2   = document.getElementById('pw2');
const match = document.getElementById('match-msg');
pw2.addEventListener('input', () => {
    if (!pw2.value) { match.style.display='none'; return; }
    match.style.display = 'block';
    if (pw2.value === pwField.value) {
        match.textContent = '✓ Passwords match';
        match.style.color = '#1e3a8a';
    } else {
        match.textContent = '✗ Passwords do not match';
        match.style.color = '#ef4444';
    }
});
</script>
@endpush