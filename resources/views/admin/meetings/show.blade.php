@extends('layouts.app')

@section('title', 'Meeting Details')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-video mr-2 text-blue-600"></i>Meeting Details
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.meetings.edit', $meeting->id) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm font-semibold">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="{{ route('admin.meetings.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm mt-2">
                    <i class="fas fa-arrow-left mr-1"></i>Back
                </a>
            </div>
        </div>
    </div>

    {{-- Meeting Info --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Title</p>
                <p class="font-semibold text-gray-800">{{ $meeting->title }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Type</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                    {{ ucfirst($meeting->type) }}
                </span>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Start Time</p>
                <p class="text-sm text-gray-700">{{ $meeting->start_time->format('d M Y, h:i A') }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">End Time</p>
                <p class="text-sm text-gray-700">{{ $meeting->end_time->format('d M Y, h:i A') }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    @if($meeting->status === 'scheduled') bg-green-100 text-green-700
                    @elseif($meeting->status === 'cancelled') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-600 @endif">
                    {{ ucfirst($meeting->status) }}
                </span>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Created By</p>
                <p class="text-sm text-gray-700">{{ $meeting->creator->name }}</p>
            </div>

        </div>

        {{-- Description --}}
        @if($meeting->description)
        <div class="bg-gray-50 rounded p-3 mb-4">
            <p class="text-xs text-gray-400 mb-1">Description</p>
            <p class="text-sm text-gray-700">{{ $meeting->description }}</p>
        </div>
        @endif

        {{-- Meet Link --}}
        <div class="bg-blue-50 rounded p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-video text-blue-500 text-xl"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-700">Google Meet Link</p>
                    <p class="text-xs text-blue-500">{{ $meeting->meet_link }}</p>
                </div>
            </div>
            <a href="{{ $meeting->meet_link }}" target="_blank"
               class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold">
                <i class="fas fa-external-link-alt mr-1"></i>Join
            </a>
        </div>

    </div>

    {{-- Participants + Attendance --}}
    <div class="bg-white rounded-lg shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-blue-600"></i>
                Participants & Attendance
                @if($meeting->for === 'all')
                    <span class="text-sm font-normal text-gray-400">(All Students)</span>
                @endif
            </h2>

            {{-- Attendance Summary --}}
            @if($meeting->for === 'specific')
                <div class="flex gap-3">
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                        ✅ {{ $meeting->participants->where('attended', true)->count() }} Attended
                    </span>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                        ❌ {{ $meeting->participants->where('attended', false)->count() }} Absent
                    </span>
                </div>
            @endif
        </div>

        @if($meeting->for === 'all')
            <p class="text-gray-500 text-sm">This meeting was open to all students.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Joined At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($meeting->participants as $participant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-400 text-sm"></i>
                                        </div>
                                        <p class="font-medium text-gray-800">
                                            {{ $participant->user->name }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $participant->user->email }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($participant->attended)
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                            ✅ Attended
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                            ❌ Absent
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $participant->joined_at 
                                        ? $participant->joined_at->format('h:i A') 
                                        : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                    No participants.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection