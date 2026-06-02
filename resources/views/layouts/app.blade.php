<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LLU University') }} — @yield('title', 'Student Portal')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --cu-blue:  #1e3a8a;
            --cu-dark:  #162d6e;
            --cu-gold:  #C9A84C;
        }
        body { font-family: 'DM Sans', sans-serif; background: #F0F4F8; }
        .font-display { font-family: 'EB Garamond', serif; }

        /* Top navbar */
        .topbar {
            background: linear-gradient(90deg, #162d6e 0%, #1e3a8a 100%);
            border-bottom: 3px solid var(--cu-gold);
        }

        /* Dropdown */
        .nav-dropdown { position: relative; }
        .nav-dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 8px);
            min-width: 200px;
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 100;
            overflow: hidden;
        }
        .nav-dropdown:hover .nav-dropdown-menu,
        .nav-dropdown:focus-within .nav-dropdown-menu { display: block; }

        /* Alert banners */
        .alert {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.875rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Gold top accent */
        .card-gold { border-top: 3px solid var(--cu-gold); border-radius: 10px; }
    </style>

    @stack('styles')
</head>

<body>

<!-- ─── Top Navigation Bar ─────────────────────────────────── -->
<nav class="topbar h-16 flex items-center px-6 sticky top-0 z-50 shadow-lg">

    <!-- Logo -->
    <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
       class="flex items-center gap-3 mr-8">
        <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center border border-white/20">
            <i class="fas fa-university text-yellow-300 text-base"></i>
        </div>
        <div class="hidden sm:block">
            <p class="text-white font-display font-bold text-base leading-tight tracking-wide">LLU University</p>
            <p class="text-white/55 text-xs">Lahore Leads University</p>
        </div>
    </a>

    <!-- Spacer -->
    <div class="flex-1"></div>

    @auth
    <!-- Nav links (desktop) -->
    <div class="hidden md:flex items-center gap-1 mr-4">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
            </a>
            <a href="{{ route('admin.applications.index') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-file-alt mr-1"></i>Applications
            </a>
            <a href="{{ route('admin.students.index') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-users mr-1"></i>Students
            </a>
        @else
            <a href="{{ route('student.dashboard') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-home mr-1"></i>Dashboard
            </a>
            <a href="{{ route('student.applications.index') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-file-alt mr-1"></i>Applications
            </a>
            <a href="{{ route('student.profile.show') }}"
               class="px-3 py-1.5 rounded text-white/80 hover:text-white hover:bg-white/10 text-sm transition">
               <i class="fas fa-user mr-1"></i>Profile
            </a>
        @endif
    </div>

    <!-- User Dropdown -->
    <div class="nav-dropdown flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-white/10 transition">
        <div class="w-8 h-8 rounded-full bg-yellow-400/80 flex items-center justify-center text-blue-900 font-bold text-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="hidden sm:block text-right">
            <p class="text-white text-sm font-medium leading-tight">{{ auth()->user()->name }}</p>
            <p class="text-white/50 text-xs">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Student' }}</p>
        </div>
        <i class="fas fa-chevron-down text-white/60 text-xs ml-1"></i>

        <div class="nav-dropdown-menu">
            <div class="px-4 py-3 border-b border-gray-100">
                <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
            @unless(auth()->user()->isAdmin())
            <a href="{{ route('student.profile.show') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <i class="fas fa-user-circle text-blue-700 w-4"></i> My Profile
            </a>
            @endunless
            <div class="border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt w-4"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

</nav>

<!-- ─── Flash Messages ─────────────────────────────────────── -->
<div class="max-w-7xl mx-auto px-4 mt-4 space-y-2">
    @if(session('success'))
        <div class="alert bg-green-50 border border-green-200 text-green-800">
            <span><i class="fas fa-check-circle mr-2 text-green-500"></i>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert bg-red-50 border border-red-200 text-red-800">
            <span><i class="fas fa-exclamation-circle mr-2 text-red-500"></i>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert bg-yellow-50 border border-yellow-200 text-yellow-800">
            <span><i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>{{ session('warning') }}</span>
            <button onclick="this.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert bg-red-50 border border-red-200 text-red-800 flex-col items-start gap-1">
            <p class="font-semibold text-sm"><i class="fas fa-exclamation-circle mr-2"></i>Please fix these errors:</p>
            <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- ─── Main Content ───────────────────────────────────────── -->
<main class="max-w-7xl mx-auto px-4 py-6">
    @yield('content')
</main>

<!-- ─── Footer ────────────────────────────────────────────── -->
<footer class="mt-10 py-5 border-t border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-400">
        <p>© {{ date('Y') }} Lahore Leads University (LLU) — Student Information System</p>
        <div class="flex gap-4">
            <a href="#" class="hover:text-blue-700 transition">Privacy Policy</a>
            <a href="#" class="hover:text-blue-700 transition">Terms of Use</a>
            <a href="mailto:helpdesk@llu.edu.pk" class="hover:text-blue-700 transition">IT Helpdesk</a>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>