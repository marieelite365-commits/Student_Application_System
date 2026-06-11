@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ $user->name }}! 
                </h1>
                <p class="text-gray-500 mt-1">
                    @if($profile)
                        Student ID: <span class="font-semibold text-blue-700">{{ $profile->student_id }}</span>
                    @else
                        <span class="text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Profile incomplete —
                            <a href="{{ route('student.profile.create') }}" class="underline">Complete now</a>
                        </span>
                    @endif
                </p>
            </div>
            {{-- ✅ NAYA --}}
    <div class="flex items-center gap-4">

    {{-- Notification Bell --}}
    <div class="relative">
    <button onclick="toggleNotifications()"
            class="relative p-2 text-gray-500 hover:text-blue-600 focus:outline-none">
        <i class="fas fa-bell text-2xl"></i>
        @if($unreadCount > 0)
            <span id="notif-count"
                  class="absolute top-0 right-0 bg-red-500 text-white text-xs
                         rounded-full w-5 h-5 flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

        {{-- Dropdown --}}
        <div id="notifDropdown"
             class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border">
            <div class="px-4 py-3 border-b flex justify-between items-center">
                <p class="font-semibold text-gray-800">Notifications</p>
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('student.notifications.markAllRead') }}">
                        @csrf
                        <button class="text-xs text-blue-600 hover:underline">Mark all read</button>
                    </form>
                @endif
            </div>
            <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                @forelse($notifications as $notif)
                    <div class="px-4 py-3 hover:bg-gray-50 {{ !$notif->is_read ? 'bg-blue-50' : '' }}">
                        <p class="text-sm font-medium text-gray-800">{{ $notif->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notif->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-gray-400 text-sm">
                        No notifications yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Profile Photo --}}
    @if($user->profile_photo_path)
        <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
             class="w-16 h-16 rounded-full object-cover border-4 border-blue-200">
    @else
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-user text-blue-400 text-2xl"></i>
        </div>
    @endif

    </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-gray-400">{{ $stats['draft'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Draft</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['submitted'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Submitted</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['under_review'] }}</p>
            <p class="text-sm text-gray-500 mt-1">In Review</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Approved</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <p class="text-3xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Rejected</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('student.applications.create') }}"
           class="bg-blue-700 hover:bg-blue-800 text-white rounded-lg shadow p-5 flex items-center gap-4 transition">
            <i class="fas fa-plus-circle text-3xl"></i>
            <div>
                <p class="font-semibold text-lg">New Application</p>
                <p class="text-blue-200 text-sm">Apply for admission</p>
            </div>
        </a>
        <a href="{{ route('student.profile.show') }}"
           class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
            <i class="fas fa-user-edit text-3xl text-blue-600"></i>
            <div>
                <p class="font-semibold text-lg">My Profile</p>
                <p class="text-gray-500 text-sm">View & update profile</p>
            </div>
        </a>
        <a href="{{ route('student.applications.index') }}"
           class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
            <i class="fas fa-list text-3xl text-blue-600"></i>
            <div>
                <p class="font-semibold text-lg">My Applications</p>
                <p class="text-gray-500 text-sm">Track your applications</p>
            </div>
        </a>
         <a href="{{ route('student.meetings.index') }}"
       class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
        <i class="fas fa-video text-3xl text-green-600"></i>
        <div>
            <p class="font-semibold text-lg">My Meetings</p>
            <p class="text-gray-500 text-sm">View & join meetings</p>
        </div>
        </a>
        <a href="{{ route('student.interviews.index') }}"
          class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
          <i class="fas fa-user-clock text-3xl text-indigo-600"></i>
         <div>
           <p class="font-semibold text-lg">My Interview</p>
           <p class="text-gray-500 text-sm">View interview schedule</p>
         </div>
        </a>
        <a href="{{ $entryTest ? route('student.entry_tests.start', $entryTest->id) : '#' }}"
            class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
              <i class="fas fa-file-alt text-3xl text-green-600"></i>
           <div>
           <p class="font-semibold text-lg">Entry Test</p>
           <p class="text-gray-500 text-sm">Take your entry test</p>
           </div>
        </a>

    {{-- ✅ Classroom Button --}}
    <a href="#courses-section"
   class="bg-white hover:bg-gray-50 text-gray-800 rounded-lg shadow p-5 flex items-center gap-4 transition border">
    <i class="fas fa-chalkboard-teacher text-3xl text-teal-600"></i>
    <div>
        <p class="font-semibold text-lg">My Courses</p>
        <p class="text-gray-500 text-sm">View & join courses</p>
    </div>
    </a>
    </div>

    {{-- Classroom Courses --}}
@if($courses->count() > 0)
<div class="bg-white rounded-lg shadow mb-6" id="courses-section">
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-chalkboard-teacher mr-2 text-teal-600"></i>My Courses
        </h2>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($courses as $course)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">{{ $course->name }}</p>
                    <div class="flex gap-2 mt-1 flex-wrap">
                        @if($course->section)
                            <span class="text-xs bg-teal-100 text-teal-700 px-2 py-1 rounded-full">
                                {{ $course->section }}
                            </span>
                        @endif
                        @if($course->enrollment_code)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                <i class="fas fa-key mr-1"></i>Code: {{ $course->enrollment_code }}
                            </span>
                        @endif
                    </div>
                    @if($course->description)
                        <p class="text-xs text-gray-500 mt-1">{{ $course->description }}</p>
                    @endif
                </div>
                <div class="flex flex-col items-end gap-2">
                    
    {{-- Enrollment Code --}}
    @if($course->enrollment_code)
    <div class="bg-teal-50 border border-teal-200 rounded px-3 py-2 text-center">
        <p class="text-xs text-teal-500">Enrollment Code</p>
        <p class="font-bold text-teal-700 text-lg tracking-wider">
            {{ $course->enrollment_code }}
        </p>
    </div>
    @endif

            {{-- Join Button --}}
                <a href="https://classroom.google.com" target="_blank"
                 class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-semibold">
                 <i class="fas fa-external-link-alt mr-1"></i>Go to Classroom
              </a>
              </div>
            </div>
        @endforeach
    </div>
</div>
@endif
    {{-- Upcoming Meetings --}}
@if($upcomingMeetings->count() > 0)
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-video mr-2 text-blue-600"></i>Upcoming Meetings
        </h2>
        <a href="{{ route('student.meetings.index') }}"
           class="text-blue-600 text-sm hover:underline">View all</a>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($upcomingMeetings as $meeting)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">{{ $meeting->title }}</p>
                    <div class="flex gap-2 mt-1">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            {{ ucfirst($meeting->type) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $meeting->start_time->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('student.meetings.join', $meeting->id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-semibold">
                    <i class="fas fa-video mr-1"></i>Join
                </a>
            </div>
        @endforeach
    </div>
  </div>
  @endif
  {{-- Past Meetings --}}
  @if(isset($pastMeetings) && $pastMeetings->count() > 0)
  <div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-history mr-2 text-gray-500"></i>Past Meetings
        </h2>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($pastMeetings as $meeting)
            @php
                $participant = $meeting->participants
                    ->where('user_id', auth()->id())
                    ->first();
                $attended = $participant?->attended ?? false;
            @endphp
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">{{ $meeting->title }}</p>
                    <div class="flex gap-2 mt-1 flex-wrap">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            {{ ucfirst($meeting->type) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $meeting->start_time->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                @if($meeting->for === 'all')
                    <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">
                        General Meeting
                    </span>
                @elseif($attended)
                    <div class="text-right">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            ✅ Attended
                        </span>
                        @if($participant->joined_at)
                            <p class="text-xs text-gray-400 mt-1">
                                Joined at {{ $participant->joined_at->format('h:i A') }}
                            </p>
                        @endif
                    </div>
                @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                        ❌ Absent
                    </span>
                @endif
            </div>
        @endforeach
    </div>
  </div>
  @endif
    <!-- Recent Applications -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-clock mr-2 text-blue-600"></i>Recent Applications
            </h2>
            <a href="{{ route('student.applications.index') }}"
               class="text-blue-600 text-sm hover:underline">View all</a>
        </div>

        @if($applications->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400">
                <i class="fas fa-folder-open text-4xl mb-3"></i>
                <p>No applications yet.</p>
                <a href="{{ route('student.applications.create') }}"
                   class="mt-3 inline-block bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                    Apply Now
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left">Program</th>
                            <th class="px-6 py-3 text-left">Department</th>
                            <th class="px-6 py-3 text-left">Semester</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($applications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium">{{ $application->degree_program }}</td>
                                <td class="px-6 py-3">{{ $application->department }}</td>
                                <td class="px-6 py-3">{{ $application->semester }} {{ $application->admission_year }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($application->status === 'approved') bg-green-100 text-green-700
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-700
                                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-700
                                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-700
                                        @elseif($application->status === 'enrolled') bg-purple-100 text-purple-700
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ $application->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('student.applications.show', $application->id) }}"
                                       class="text-blue-600 hover:underline">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script>
    function toggleNotifications() {
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notif-count');

    dropdown.classList.toggle('hidden');

    // Jab open ho aur badge visible ho — mark all read
    if (!dropdown.classList.contains('hidden') && badge) {
        fetch('{{ route("student.notifications.markAllRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(() => {
            badge.remove(); // Badge hatao
            // Blue background bhi hatao
            document.querySelectorAll('#notifDropdown .bg-blue-50')
                    .forEach(el => el.classList.remove('bg-blue-50'));
        });
     }
    }

    // Bahar click karne pe close ho
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notifDropdown');
        if (!e.target.closest('.relative')) {
            dropdown.classList.add('hidden');
        }
    });
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif
</script>

@endsection