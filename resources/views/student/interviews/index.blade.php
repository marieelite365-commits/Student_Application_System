@extends('layouts.app')
@section('title', 'My Interview')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-white text-xs"></i>
            </span>
            My Interview
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">Your scheduled admission interview</p>
    </div>
    <a href="{{ route('student.dashboard') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Dashboard
    </a>
</div>

@forelse($interviews as $interview)

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-gray-400">Roll No</p>
                <p class="text-lg font-bold text-indigo-700">{{ $interview->student_roll_no }}</p>
            </div>
            @php
                $statusColors = [
                    'scheduled'   => 'bg-blue-100 text-blue-700',
                    'rescheduled' => 'bg-yellow-100 text-yellow-700',
                    'completed'   => 'bg-green-100 text-green-700',
                    'cancelled'   => 'bg-red-100 text-red-700',
                    'no_show'     => 'bg-gray-100 text-gray-600',
                ];
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($interview->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
            <div>
                <p class="text-gray-400 text-xs">Program</p>
                <p class="font-medium text-gray-800">{{ $interview->application->degree_program }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">Date</p>
                <p class="font-medium text-gray-800">{{ $interview->scheduled_at->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">Time</p>
                <p class="font-medium text-gray-800">{{ $interview->scheduled_at->format('h:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">Duration</p>
                <p class="font-medium text-gray-800">{{ $interview->duration }} minutes</p>
            </div>
        </div>

        {{-- Result Badge --}}
        @if($interview->result !== 'pending')
            <div class="mb-4">
                @if($interview->result === 'pass')
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-check-circle mr-1"></i> Interview Passed
                    </span>
                @else
                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fas fa-times-circle mr-1"></i> Interview Failed
                    </span>
                @endif
            </div>
        @endif

        <div class="flex gap-2">
            <a href="{{ route('student.interviews.show', $interview->id) }}"
               class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fas fa-eye mr-1"></i> View Details
            </a>
            @if($interview->status === 'scheduled')
                <a href="{{ $interview->zoom_join_url }}" target="_blank"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fas fa-video mr-1"></i> Join Interview
                </a>
            @endif
        </div>
    </div>

@empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500 font-medium">No interview scheduled yet.</p>
        <p class="text-gray-400 text-sm mt-1">Interview will appear here once admin schedules it.</p>
    </div>
@endforelse

@endsection