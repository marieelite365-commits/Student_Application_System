@extends('layouts.app')

@section('title', 'All Applications')

@section('content')

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-file-alt mr-2 text-blue-600"></i>All Applications
            </h1>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.applications.index') }}"
                  class="flex flex-wrap gap-3 items-end">

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Student name or email..."
                           class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status</label>
                    <select name="status"
                            class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="draft"        {{ request('status') === 'draft'        ? 'selected' : '' }}>Draft</option>
                        <option value="submitted"    {{ request('status') === 'submitted'    ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved"     {{ request('status') === 'approved'     ? 'selected' : '' }}>Approved</option>
                        <option value="rejected"     {{ request('status') === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                        <option value="enrolled"     {{ request('status') === 'enrolled'     ? 'selected' : '' }}>Enrolled</option>
                    </select>
                </div>

                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm transition">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>

                @if(request('search') || request('status'))
                    <a href="{{ route('admin.applications.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm py-2">
                        <i class="fas fa-times mr-1"></i>Clear
                    </a>
                @endif

            </form>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Program</th>
                            <th class="px-4 py-3 text-left">Semester</th>
                            <th class="px-4 py-3 text-left">Documents</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Submitted</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400">{{ $application->id }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $application->user->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $application->studentProfile->student_id ?? '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <p>{{ $application->degree_program }}</p>
                                    <p class="text-xs text-gray-400">{{ $application->department }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $application->semester }} {{ $application->admission_year }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">
                                        {{ $application->documents->count() }}
                                    </span>
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
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $application->submitted_at
                                        ? $application->submitted_at->format('d M Y')
                                        : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.applications.show', $application->id) }}"
                                       class="text-blue-600 hover:underline text-xs">
                                        <i class="fas fa-eye mr-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                    <i class="fas fa-folder-open text-3xl mb-2"></i>
                                    <p>No applications found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection