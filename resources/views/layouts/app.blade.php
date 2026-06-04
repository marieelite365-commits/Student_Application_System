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
            --cu-blue:    #1e3a8a;
            --cu-dark:    #162d6e;
            --cu-gold:    #C9A84C;
            --sidebar-w:  260px;
        }
        body { font-family: 'DM Sans', sans-serif; background: #F0F4F8; }
        .font-display { font-family: 'EB Garamond', serif; }

        /* ── Top Navbar ── */
        .topbar {
            background: linear-gradient(90deg, #162d6e 0%, #1e3a8a 100%);
            border-bottom: 3px solid var(--cu-gold);
            height: 64px;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #0f2057 0%, #162d6e 35%, #1a3480 100%);
            position: fixed;
            top: 64px;
            left: 0;
            bottom: 0;
            z-index: 40;
            overflow-y: auto;
            overflow-x: hidden;
            border-right: 1px solid rgba(255,255,255,0.06);
            box-shadow: 3px 0 20px rgba(15,32,87,0.25);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sidebar-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: rgba(201,168,76,0.65);
            padding: 16px 16px 5px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 16px;
            color: rgba(255,255,255,0.72);
            font-size: 0.835rem;
            font-weight: 400;
            border-left: 3px solid transparent;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: rgba(201,168,76,0.5);
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-left-color: var(--cu-gold);
            font-weight: 600;
        }
        .sidebar-link .s-icon {
            width: 16px;
            text-align: center;
            font-size: 0.8rem;
            flex-shrink: 0;
            opacity: 0.8;
        }
        .sidebar-link.active .s-icon { opacity: 1; }
        .sidebar-badge {
            margin-left: auto;
            background: #e53e3e;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 20px;
            flex-shrink: 0;
        }
        .sidebar-badge.gold {
            background: var(--cu-gold);
            color: #162d6e;
        }
        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.07);
            margin: 6px 0;
        }

        /* ── Admin Layout ── */
        .admin-layout { display: flex; min-height: calc(100vh - 64px); }
        .admin-main   { margin-left: var(--sidebar-w); flex: 1; min-width: 0; }

        /* ── Dropdown ── */
        .nav-dropdown { position: relative; }
        .nav-dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 8px);
            min-width: 210px;
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.13);
            z-index: 200;
            overflow: hidden;
        }
        .nav-dropdown:hover .nav-dropdown-menu,
        .nav-dropdown:focus-within .nav-dropdown-menu { display: block; }

        /* ── Flash alerts ── */
        .alert {
            border-radius: 8px;
            padding: 11px 15px;
            font-size: 0.875rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar   { transform: translateX(-100%); transition: transform 0.25s ease; }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ══ TOP NAVBAR ═══════════════════════════════════════════ -->
<nav class="topbar flex items-center px-5 sticky top-0 z-50 shadow-lg">

    @auth
    @if(auth()->user()->isAdmin())
    <button id="sidebarToggle" class="md:hidden mr-3 text-white/70 hover:text-white transition">
        <i class="fas fa-bars text-lg"></i>
    </button>
    @endif
    @endauth

    <!-- Logo -->
    <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
       class="flex items-center gap-3 mr-6">
        <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center border border-white/20">
            <i class="fas fa-university text-yellow-300 text-sm"></i>
        </div>
        <div class="hidden sm:block">
            <p class="text-white font-display font-bold text-sm leading-tight tracking-wide">LLU University</p>
            <p class="text-white/50 text-[10px]">Lahore Leads University</p>
        </div>
    </a>

    <div class="flex-1"></div>

    @auth

    {{-- Student nav links (admin uses sidebar) --}}
    @unless(auth()->user()->isAdmin())
    <div class="hidden md:flex items-center gap-1 mr-4">
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
    </div>
    @endunless

    {{-- Notification bell for admin --}}
    @if(auth()->user()->isAdmin())
    <button class="relative mr-2 w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/10 transition text-white/70 hover:text-white">
        <i class="fas fa-bell text-sm"></i>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-yellow-400 rounded-full border border-blue-900"></span>
    </button>
    @endif

    <!-- User dropdown -->
    <div class="nav-dropdown flex items-center gap-2 cursor-pointer px-2.5 py-1.5 rounded-lg hover:bg-white/10 transition">
        <div class="w-8 h-8 rounded-full bg-yellow-400/80 flex items-center justify-center text-blue-900 font-bold text-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="hidden sm:block">
            <p class="text-white text-xs font-semibold leading-tight">{{ auth()->user()->name }}</p>
            <p class="text-white/50 text-[10px]">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Student' }}</p>
        </div>
        <i class="fas fa-chevron-down text-white/50 text-[10px] ml-1"></i>

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
<!-- ══ END NAVBAR ════════════════════════════════════════════ -->


@auth

@if(auth()->user()->isAdmin())
{{-- ════════════════════════════════════════════════════════════
     ADMIN LAYOUT  —  Sidebar + Content
════════════════════════════════════════════════════════════ --}}
<div class="admin-layout">

    <!-- ══ LEFT BLUE SIDEBAR ═══════════════════════════════════ -->
    <aside class="sidebar" id="adminSidebar">

        <!-- Admin Mini Profile -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-white/10">
            <div class="w-9 h-9 rounded-full bg-yellow-400/80 flex items-center justify-center text-blue-900 font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-white/40 text-[10px]">System Administrator</p>
            </div>
        </div>

        <!-- ── MAIN ────────────────────────────── -->
        <p class="sidebar-section-label">Main</p>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large s-icon"></i> Dashboard
        </a>

        <!-- ── PEOPLE ──────────────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">People</p>

        <a href="#" class="sidebar-link {{ request()->routeIs('admin.visitors*') ? 'active' : '' }}">
            <i class="fas fa-eye s-icon"></i> Visitors
            <span class="sidebar-badge gold">Live</span>
        </a>

        <a href="#" class="sidebar-link {{ request()->routeIs('admin.candidates*') ? 'active' : '' }}">
            <i class="fas fa-user-clock s-icon"></i> Candidates
            @if(!empty($newCandidatesCount) && $newCandidatesCount > 0)
                <span class="sidebar-badge">{{ $newCandidatesCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.students.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate s-icon"></i> Students
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chalkboard-teacher s-icon"></i> Faculty
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-user-tie s-icon"></i> Staff
        </a>

        <!-- ── ADMISSIONS ─────────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">Admissions</p>

        <a href="{{ route('admin.applications.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">
            <i class="fas fa-folder-open s-icon"></i> All Applications
        </a>

        <a href="{{ route('admin.applications.index') }}?status=submitted"
           class="sidebar-link">
            <i class="fas fa-inbox s-icon"></i> New Submissions
        </a>

        <a href="{{ route('admin.applications.index') }}?status=pending"
           class="sidebar-link">
            <i class="fas fa-phone-alt s-icon"></i> Contacted / Pending
        </a>

        <a href="{{ route('admin.applications.index') }}?status=under_review"
           class="sidebar-link">
            <i class="fas fa-search s-icon"></i> Under Review
        </a>

        <a href="{{ route('admin.applications.index') }}?status=approved"
           class="sidebar-link">
            <i class="fas fa-check-circle s-icon"></i> Approved
        </a>

        <a href="{{ route('admin.applications.index') }}?status=enrolled"
           class="sidebar-link">
            <i class="fas fa-id-card s-icon"></i> Enrolled
        </a>

        <a href="{{ route('admin.applications.index') }}?status=rejected"
           class="sidebar-link">
            <i class="fas fa-times-circle s-icon"></i> Rejected
        </a>

        <!-- ── ACADEMICS ──────────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">Academics</p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-building s-icon"></i> Departments
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-graduation-cap s-icon"></i> Programs & Degrees
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-book s-icon"></i> Courses
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-calendar-alt s-icon"></i> Academic Calendar
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-tasks s-icon"></i> Exams & Results
        </a>

        <!-- ── FINANCE ────────────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">Finance</p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-file-invoice-dollar s-icon"></i> Fee Management
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-hand-holding-usd s-icon"></i> Scholarships
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-receipt s-icon"></i> Payment Records
        </a>

        <!-- ── COMMUNICATION ──────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">Communication</p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-bullhorn s-icon"></i> Announcements
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-envelope s-icon"></i> Email / Notifications
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-comments s-icon"></i> Enquiries
        </a>

        <!-- ── REPORTS & SYSTEM ────────────────── -->
        <div class="sidebar-divider"></div>
        <p class="sidebar-section-label">Reports & System</p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-bar s-icon"></i> Reports & Analytics
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-file-export s-icon"></i> Export Data
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-cog s-icon"></i> Settings
        </a>
        <a href="#" class="sidebar-link">
            <i class="fas fa-shield-alt s-icon"></i> Roles & Permissions
        </a>

        <div class="sidebar-divider"></div>

        <!-- Sign Out -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="sidebar-link w-full text-left hover:bg-red-900/30 text-red-300 hover:text-red-200">
                <i class="fas fa-sign-out-alt s-icon"></i> Sign Out
            </button>
        </form>

        <p class="text-center text-white/20 text-[10px] py-4">LLU Admin Portal © {{ date('Y') }}</p>
    </aside>
    <!-- ══ END SIDEBAR ═══════════════════════════════════════════ -->

    <!-- ══ ADMIN MAIN CONTENT ════════════════════════════════════ -->
    <div class="admin-main">

        <!-- Flash messages -->
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
                <div class="alert bg-green-50 border border-green-200 text-green-800">
                    <span><i class="fas fa-check-circle mr-2 text-green-500"></i>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 ml-4 flex-shrink-0"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert bg-red-50 border border-red-200 text-red-800">
                    <span><i class="fas fa-exclamation-circle mr-2 text-red-500"></i>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 ml-4 flex-shrink-0"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert bg-yellow-50 border border-yellow-200 text-yellow-800">
                    <span><i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>{{ session('warning') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600 ml-4 flex-shrink-0"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert bg-red-50 border border-red-200 text-red-800 flex-col items-start gap-1">
                    <p class="font-semibold text-sm"><i class="fas fa-exclamation-circle mr-2"></i>Please fix these errors:</p>
                    <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="px-6 py-5">
            @yield('content')
        </main>

        <footer class="mt-4 py-4 border-t border-gray-200 bg-white">
            <div class="px-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-400">
                <p>© {{ date('Y') }} Lahore Leads University (LLU) — Admin Portal</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-blue-700 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-blue-700 transition">Terms of Use</a>
                    <a href="mailto:helpdesk@llu.edu.pk" class="hover:text-blue-700 transition">IT Helpdesk</a>
                </div>
            </div>
        </footer>
    </div>
    <!-- ══ END ADMIN MAIN ════════════════════════════════════════ -->

</div>

@else
{{-- ════════════════════════════════════════════════════════════
     STUDENT LAYOUT  —  No sidebar, same as before
════════════════════════════════════════════════════════════ --}}

<!-- Flash messages -->
<div class="max-w-7xl mx-auto px-4 mt-4 space-y-2">
    @if(session('success'))
        <div class="alert bg-green-50 border border-green-200 text-green-800">
            <span><i class="fas fa-check-circle mr-2 text-green-500"></i>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 ml-4"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert bg-red-50 border border-red-200 text-red-800">
            <span><i class="fas fa-exclamation-circle mr-2 text-red-500"></i>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 ml-4"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert bg-yellow-50 border border-yellow-200 text-yellow-800">
            <span><i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>{{ session('warning') }}</span>
            <button onclick="this.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600 ml-4"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert bg-red-50 border border-red-200 text-red-800 flex-col items-start gap-1">
            <p class="font-semibold text-sm"><i class="fas fa-exclamation-circle mr-2"></i>Please fix these errors:</p>
            <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
</div>

<main class="max-w-7xl mx-auto px-4 py-6">
    @yield('content')
</main>

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

@endif
@endauth

@stack('scripts')
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar   = document.getElementById('adminSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
</script>
</body>
</html>