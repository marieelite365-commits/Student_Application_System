@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>Admin Dashboard
        </h1>
        <p class="text-gray-500 mt-1">University Portal Management</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-600">
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Students</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-gray-400">
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_apps'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Applications</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['submitted'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Pending Review</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Approved</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-400">
            <p class="text-3xl font-bold text-orange-500">{{ $stats['under_review'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Under Review</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Rejected</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['enrolled'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Enrolled</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-gray-300">
            <p class="text-3xl font-bold text-gray-400">{{ $stats['draft'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Drafts</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Applications -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock mr-2 text-blue-600"></i>Recent Applications
                </h2>
                <a href="{{ route('admin.applications.index') }}"
                   class="text-blue-600 text-sm hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Program</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentApplications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $application->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $application->user->email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p>{{ $application->degree_program }}</p>
                                    <p class="text-xs text-gray-400">{{ $application->department }}</p>
                                </td>
                                <td class="px-4 py-3">
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
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.applications.show', $application->id) }}"
                                       class="text-blue-600 hover:underline text-xs">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                    No applications yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-600"></i>Recent Students
                </h2>
                <a href="{{ route('admin.students.index') }}"
                   class="text-blue-600 text-sm hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentStudents as $student)
                    <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            @if($student->profile_photo_path)
                                <img src="{{ asset('storage/' . $student->profile_photo_path) }}"
                                     class="w-9 h-9 rounded-full object-cover">
                            @else
                                <i class="fas fa-user text-blue-400 text-sm"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $student->name }}</p>
                            <p class="text-xs text-gray-400">{{ $student->email }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-6 text-center text-gray-400 text-sm">
                        No students yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection