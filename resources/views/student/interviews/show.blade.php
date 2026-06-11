@extends('layouts.app')
@section('title', 'Interview Details')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-white text-xs"></i>
            </span>
            Interview Details
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">{{ $interview->student_roll_no }}</p>
    </div>
    <a href="{{ route('student.interviews.index') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Interview Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-info-circle mr-1 text-indigo-500"></i> Interview Information
            </p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Roll No</p>
                    <p class="font-bold text-indigo-700 text-base">{{ $interview->student_roll_no }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Status</p>
                    @php
                        $statusColors = [
                            'scheduled'   => 'bg-blue-100 text-blue-700',
                            'rescheduled' => 'bg-yellow-100 text-yellow-700',
                            'completed'   => 'bg-green-100 text-green-700',
                            'cancelled'   => 'bg-red-100 text-red-700',
                            'no_show'     => 'bg-gray-100 text-gray-600',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($interview->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Degree Program</p>
                    <p class="font-medium text-gray-800">{{ $interview->application->degree_program }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Department</p>
                    <p class="font-medium text-gray-800">{{ $interview->application->department }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Date & Time</p>
                    <p class="font-medium text-gray-800">{{ $interview->scheduled_at->format('d M Y, h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Duration</p>
                    <p class="font-medium text-gray-800">{{ $interview->duration }} minutes</p>
                </div>
            </div>
        </div>

        {{-- Zoom Info --}}
        @if($interview->status === 'scheduled')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-video mr-1 text-blue-500"></i> Zoom Meeting Details
            </p>
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <p class="text-gray-400 text-xs">Meeting ID</p>
                    <p class="font-medium text-gray-800">{{ $interview->zoom_meeting_id }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Password</p>
                    <p class="font-medium text-gray-800">{{ $interview->zoom_password }}</p>
                </div>
            </div>

            {{-- Join Button --}}
            <a href="{{ $interview->zoom_join_url }}" target="_blank"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                <i class="fas fa-video mr-2"></i> Join Interview on Zoom
            </a>
        </div>
        @endif

        {{-- Result --}}
        @if($interview->result !== 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-clipboard-check mr-1 text-green-500"></i> Interview Result
            </p>
            @if($interview->result === 'pass')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <i class="fas fa-check-circle text-green-500 text-3xl mb-2 block"></i>
                    <p class="font-bold text-green-700 text-lg">Congratulations! You Passed!</p>
                    <p class="text-green-600 text-sm mt-1">You have cleared the interview round.</p>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <i class="fas fa-times-circle text-red-500 text-3xl mb-2 block"></i>
                    <p class="font-bold text-red-700 text-lg">Interview Not Cleared</p>
                    <p class="text-red-600 text-sm mt-1">Unfortunately you did not pass the interview.</p>
                </div>
            @endif
            @if($interview->interviewer_notes)
                <div class="mt-3 bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500 font-semibold mb-1">Interviewer Notes:</p>
                    <p class="text-sm text-gray-700">{{ $interview->interviewer_notes }}</p>
                </div>
            @endif
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Countdown --}}
        @if($interview->status === 'scheduled')
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 text-center">
            <p class="text-xs font-semibold text-indigo-600 mb-1">Interview In</p>
            <p class="text-2xl font-bold text-indigo-700" id="countdown">
                {{ $interview->scheduled_at->diffForHumans() }}
            </p>
            <p class="text-xs text-indigo-500 mt-1">
                {{ $interview->scheduled_at->format('d M Y, h:i A') }}
            </p>
        </div>
        @endif

        {{-- Tips --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-lightbulb mr-1 text-yellow-500"></i> Interview Tips
            </p>
            <ul class="text-xs text-gray-500 space-y-2">
                <li><i class="fas fa-check text-green-500 mr-1"></i> Join 5 minutes early</li>
                <li><i class="fas fa-check text-green-500 mr-1"></i> Check your camera & mic</li>
                <li><i class="fas fa-check text-green-500 mr-1"></i> Sit in a quiet place</li>
                <li><i class="fas fa-check text-green-500 mr-1"></i> Keep documents ready</li>
                <li><i class="fas fa-check text-green-500 mr-1"></i> Dress professionally</li>
            </ul>
        </div>

    </div>
</div>

@endsection