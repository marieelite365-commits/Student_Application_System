@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ $user->name }}! 👋
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
            <div class="text-right">
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
    </div>

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

@endsection