@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

{{-- ── Page Header ─────────────────────────────────────────── --}}
<div class="flex items-start justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 font-display flex items-center gap-2">
            <span class="w-7 h-7 bg-blue-700 rounded-lg flex items-center justify-center">
                <i class="fas fa-th-large text-white text-xs"></i>
            </span>
            Admin Dashboard
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">
            Lahore Leads University &mdash; Admissions Management System
        </p>
    </div>
    <div class="text-right hidden sm:block">
        <p class="text-xs font-semibold text-gray-700">{{ now()->format('l') }}</p>
        <p class="text-xs text-gray-400">{{ now()->format('d M Y') }}</p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 1 — PEOPLE FUNNEL
     Visitor → Candidate → Student
══════════════════════════════════════════════════════════════ --}}
<p class="section-label text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">People Funnel</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

    {{-- Visitors --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-eye text-sky-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['visitors'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Visitors</p>
            <p class="text-[10px] text-gray-400 mt-0.5">Registered, no application yet</p>
        </div>
    </div>

    {{-- Candidates --}}
    <div class="bg-white rounded-xl border border-indigo-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-clock text-indigo-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['candidates'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Candidates</p>
            <p class="text-[10px] text-gray-400 mt-0.5">Application submitted</p>
        </div>
    </div>

    {{-- Students (Enrolled) --}}
    <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-graduate text-blue-700 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['enrolled'] }}</p>
            <p class="text-xs text-gray-500 font-medium">Enrolled Students</p>
            <p class="text-[10px] text-gray-400 mt-0.5">Confirmed admission</p>
        </div>
    </div>

</div>

{{-- Funnel arrow visual --}}
<div class="hidden sm:flex items-center justify-center gap-2 mb-5 -mt-2">
    <div class="flex-1 h-px bg-gray-200"></div>
    <div class="flex items-center gap-3 text-xs text-gray-400">
        <span class="bg-sky-100 text-sky-600 px-3 py-1 rounded-full font-semibold">Visit</span>
        <i class="fas fa-arrow-right text-gray-300"></i>
        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full font-semibold">Apply</span>
        <i class="fas fa-arrow-right text-gray-300"></i>
        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-semibold">Contact</span>
        <i class="fas fa-arrow-right text-gray-300"></i>
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">Enroll</span>
    </div>
    <div class="flex-1 h-px bg-gray-200"></div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 2 — APPLICATION PIPELINE
══════════════════════════════════════════════════════════════ --}}
<p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Application Pipeline</p>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-5">

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-gray-300 text-center hover:shadow-md transition">
        <i class="fas fa-folder text-gray-400 text-sm mb-1"></i>
        <p class="text-xl font-bold text-gray-700">{{ $stats['total_apps'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Total</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-gray-200 text-center hover:shadow-md transition">
        <i class="fas fa-pencil-alt text-gray-300 text-sm mb-1"></i>
        <p class="text-xl font-bold text-gray-400">{{ $stats['draft'] }}</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Draft</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-blue-400 text-center hover:shadow-md transition">
        <i class="fas fa-inbox text-blue-400 text-sm mb-1"></i>
        <p class="text-xl font-bold text-blue-600">{{ $stats['submitted'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Submitted</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-yellow-400 text-center hover:shadow-md transition">
        <i class="fas fa-phone-alt text-yellow-500 text-sm mb-1"></i>
        <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Contacted</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-orange-400 text-center hover:shadow-md transition">
        <i class="fas fa-search text-orange-400 text-sm mb-1"></i>
        <p class="text-xl font-bold text-orange-500">{{ $stats['under_review'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Under Review</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-green-500 text-center hover:shadow-md transition">
        <i class="fas fa-check-circle text-green-500 text-sm mb-1"></i>
        <p class="text-xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Approved</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-t-2 border-red-400 text-center hover:shadow-md transition">
        <i class="fas fa-times-circle text-red-400 text-sm mb-1"></i>
        <p class="text-xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
        <p class="text-[10px] text-gray-500 mt-0.5">Rejected</p>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 3 — SUMMARY CARDS ROW
══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Admission Rate --}}
    <div class="bg-gradient-to-br from-blue-800 to-blue-950 rounded-xl p-5 flex items-center justify-between shadow-sm">
        <div>
            <p class="text-white/60 text-xs mb-1">Admission Rate</p>
            @php
                $rate = $stats['total_apps'] > 0
                    ? round(($stats['approved'] / $stats['total_apps']) * 100)
                    : 0;
            @endphp
            <p class="text-3xl font-bold text-white">{{ $rate }}%</p>
            <p class="text-white/50 text-[10px] mt-1">
                {{ $stats['approved'] }} approved / {{ $stats['total_apps'] }} total
            </p>
        </div>
        <div class="w-12 h-12 rounded-full border-2 border-yellow-400/50 flex items-center justify-center">
            <i class="fas fa-chart-pie text-yellow-300 text-lg"></i>
        </div>
    </div>

    {{-- Conversion Rate (Visitors → Candidates) --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs mb-1">Visitor → Candidate Rate</p>
            @php
                $total_users = $stats['visitors'] + $stats['candidates'];
                $conversion  = $total_users > 0
                    ? round(($stats['candidates'] / $total_users) * 100)
                    : 0;
            @endphp
            <p class="text-3xl font-bold text-indigo-600">{{ $conversion }}%</p>
            <p class="text-gray-400 text-[10px] mt-1">
                {{ $stats['candidates'] }} of {{ $total_users }} registered users
            </p>
        </div>
        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center">
            <i class="fas fa-funnel-dollar text-indigo-400 text-lg"></i>
        </div>
    </div>

    {{-- Enrollment Rate (Approved → Enrolled) --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs mb-1">Approved → Enrolled Rate</p>
            @php
                $enroll_rate = $stats['approved'] > 0
                    ? round(($stats['enrolled'] / $stats['approved']) * 100)
                    : 0;
            @endphp
            <p class="text-3xl font-bold text-green-600">{{ $enroll_rate }}%</p>
            <p class="text-gray-400 text-[10px] mt-1">
                {{ $stats['enrolled'] }} enrolled of {{ $stats['approved'] }} approved
            </p>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
            <i class="fas fa-user-check text-green-500 text-lg"></i>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 4 — TABLES + SIDEBAR PANELS
══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Recent Applications Table --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-sm font-semibold text-gray-800">
                <i class="fas fa-clock mr-2 text-blue-600"></i>Recent Applications
            </h2>
            <a href="{{ route('admin.applications.index') }}"
               class="text-blue-600 text-xs hover:underline font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase tracking-wide text-[10px]">
                    <tr>
                        <th class="px-4 py-3 text-left">Student</th>
                        <th class="px-4 py-3 text-left">Program</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentApplications as $application)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($application->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $application->user->name }}</p>
                                    <p class="text-gray-400 text-[10px]">{{ $application->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-800">{{ $application->degree_program }}</p>
                            <p class="text-gray-400 text-[10px]">{{ $application->department }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                                @if($application->status === 'approved')     bg-green-100 text-green-700
                                @elseif($application->status === 'rejected') bg-red-100 text-red-700
                                @elseif($application->status === 'under_review') bg-orange-100 text-orange-700
                                @elseif($application->status === 'submitted') bg-blue-100 text-blue-700
                                @elseif($application->status === 'pending')  bg-yellow-100 text-yellow-700
                                @elseif($application->status === 'enrolled') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-500
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.applications.show', $application->id) }}"
                               class="text-blue-600 hover:underline font-medium">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-300">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            No applications yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right panels --}}
    <div class="flex flex-col gap-4">

        {{-- Recent Enrolled Students --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-user-graduate mr-2 text-blue-600"></i>New Students
                </h2>
                <a href="{{ route('admin.students.index') }}"
                   class="text-blue-600 text-xs hover:underline font-medium">View all →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentStudents as $student)
                <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        @if($student->profile_photo_path)
                            <img src="{{ asset('storage/' . $student->profile_photo_path) }}"
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <span class="text-blue-700 font-bold text-xs">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-800 truncate">{{ $student->name }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ $student->email }}</p>
                    </div>
                    <span class="ml-auto text-[10px] bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">
                        Enrolled
                    </span>
                </div>
                @empty
                <div class="px-5 py-6 text-center text-gray-300 text-xs">
                    <i class="fas fa-user-slash text-2xl mb-1 block"></i>No enrolled students yet.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Notice Board --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-3.5 border-b border-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-bullhorn mr-2 text-yellow-500"></i>Notice Board
                </h2>
                <a href="#" class="text-blue-600 text-xs hover:underline font-medium">Manage →</a>
            </div>
            <div class="px-5 py-3 space-y-3">
                <div class="flex items-start gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></span>
                    <div>
                        <p class="text-xs font-medium text-gray-700">Fall 2025 Admissions Open</p>
                        <p class="text-[10px] text-gray-400">Last date: Aug 31, 2025</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mt-1.5 flex-shrink-0"></span>
                    <div>
                        <p class="text-xs font-medium text-gray-700">Merit List Published</p>
                        <p class="text-[10px] text-gray-400">Check admissions portal</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mt-1.5 flex-shrink-0"></span>
                    <div>
                        <p class="text-xs font-medium text-gray-700">Fee Submission Deadline</p>
                        <p class="text-[10px] text-gray-400">Sep 10, 2025</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></span>
                    <div>
                        <p class="text-xs font-medium text-gray-700">Scholarship Applications</p>
                        <p class="text-[10px] text-gray-400">Closing soon — Sep 5, 2025</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs font-semibold text-gray-600 mb-3">
                <i class="fas fa-bolt text-yellow-500 mr-1"></i>Quick Actions
            </p>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.applications.index') }}?status=submitted"
                   class="flex flex-col items-center gap-1.5 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-center">
                    <i class="fas fa-inbox text-blue-600 text-base"></i>
                    <span class="text-[10px] text-blue-700 font-semibold">Review New</span>
                </a>
                <a href="{{ route('admin.applications.index') }}?status=pending"
                   class="flex flex-col items-center gap-1.5 p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition text-center">
                    <i class="fas fa-phone-alt text-yellow-600 text-base"></i>
                    <span class="text-[10px] text-yellow-700 font-semibold">Follow-ups</span>
                </a>
                <a href="{{ route('admin.students.index') }}"
                   class="flex flex-col items-center gap-1.5 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-center">
                    <i class="fas fa-users text-purple-600 text-base"></i>
                    <span class="text-[10px] text-purple-700 font-semibold">Students</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center gap-1.5 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition text-center">
                    <i class="fas fa-chart-bar text-green-600 text-base"></i>
                    <span class="text-[10px] text-green-700 font-semibold">Reports</span>
                </a>
                <a href="{{ route('admin.admin.classroom.index') }}"
                    class="flex flex-col items-center gap-1.5 p-3 bg-teal-50 hover:bg-teal-100 rounded-lg transition text-center">
                    <i class="fas fa-chalkboard-teacher text-teal-600 text-base"></i>
                    <span class="text-[10px] text-teal-700 font-semibold">Classroom</span>
                </a>
                <a href="{{ route('admin.interviews.create') }}"
                    class="flex flex-col items-center gap-1.5 p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition text-center">
                    <i class="fas fa-user-clock text-indigo-600 text-base"></i>
                    <span class="text-[10px] text-indigo-700 font-semibold">Interviews</span>
                </a>
                <a href="{{ route('admin.entry_tests.index') }}"
                     class="flex flex-col items-center gap-1.5 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition text-center">
                     <i class="fas fa-file-alt text-green-600 text-base"></i>
                     <span class="text-[10px] text-green-700 font-semibold">Entry Tests</span>
                </a>
            </div>
        </div>

    </div>
</div>
    {{-- Notifications Bell + Meetings --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Upcoming Meetings --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-video mr-2 text-blue-600"></i>Upcoming Meetings
                </h2>
                <a href="{{ route('admin.meetings.create') }}"
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold">
                    <i class="fas fa-plus mr-1"></i>Schedule
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($upcomingMeetings as $meeting)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $meeting->title }}</p>
                            <div class="flex gap-2 mt-1 flex-wrap">
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    {{ ucfirst($meeting->type) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $meeting->start_time->format('d M Y, h:i A') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ $meeting->meet_link }}" target="_blank"
                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-video mr-1"></i>Join
                            </a>
                            <a href="{{ route('admin.meetings.show', $meeting->id) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400">
                        <i class="fas fa-video text-4xl mb-3"></i>
                        <p>No upcoming meetings.</p>
                        <a href="{{ route('admin.meetings.create') }}"
                           class="mt-3 inline-block bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Schedule Now
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Notifications --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-bell mr-2 text-blue-600"></i>Notifications
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full ml-1">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </h2>
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
                        @csrf
                        <button class="text-xs text-blue-600 hover:underline">Mark all read</button>
                    </form>
                @endif
            </div>
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                @forelse($notifications as $notif)
                    <div class="px-4 py-3 {{ !$notif->is_read ? 'bg-blue-50' : '' }}">
                        <p class="text-sm font-medium text-gray-800">{{ $notif->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notif->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-gray-400 text-sm">
                        No notifications.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Past Meetings with Attendance --}}
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-gray-500"></i>Past Meetings — Attendance
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Meeting</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Attendance</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pastMeetings as $meeting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $meeting->title }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $meeting->start_time->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    {{ ucfirst($meeting->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($meeting->for === 'all')
                                    <span class="text-xs text-gray-400">All Students</span>
                                @else
                                    <span class="font-semibold text-green-600">
                                        {{ $meeting->participants->where('attended', true)->count() }}
                                    </span>
                                    <span class="text-gray-400 text-xs">
                                        / {{ $meeting->participants->count() }} attended
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.meetings.show', $meeting->id) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                No past meetings yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection