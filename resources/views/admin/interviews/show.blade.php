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
    <a href="{{ route('admin.interviews.index') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

{{-- Success --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Student & Interview Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-user mr-1 text-indigo-500"></i> Student Information
            </p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Roll No</p>
                    <p class="font-semibold text-indigo-700">{{ $interview->student_roll_no }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Student Name</p>
                    <p class="font-medium text-gray-800">{{ $interview->student->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Email</p>
                    <p class="font-medium text-gray-800">{{ $interview->student->email }}</p>
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
                    <p class="text-gray-400 text-xs">Scheduled By</p>
                    <p class="font-medium text-gray-800">{{ $interview->scheduledBy->name }}</p>
                </div>
            </div>
        </div>

        {{-- Zoom Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-video mr-1 text-blue-500"></i> Zoom Meeting Info
            </p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Meeting ID</p>
                    <p class="font-medium text-gray-800">{{ $interview->zoom_meeting_id }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Password</p>
                    <p class="font-medium text-gray-800">{{ $interview->zoom_password }}</p>
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
            <div class="mt-4 flex gap-2">
                <a href="{{ $interview->zoom_start_url }}" target="_blank"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fas fa-video mr-1"></i> Start Meeting (Host)
                </a>
                @if($interview->drive_file_url)
                    <a href="{{ $interview->drive_file_url }}" target="_blank"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
                        <i class="fab fa-google-drive mr-1"></i> View in Drive
                    </a>
                @endif
            </div>
        </div>

        {{-- Mark Result --}}
        @if($interview->status !== 'cancelled' && $interview->result === 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-clipboard-check mr-1 text-green-500"></i> Mark Interview Result
            </p>
            <form action="{{ route('admin.interviews.result', $interview->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Result</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="result" value="pass" class="text-green-600">
                            <span class="text-sm font-medium text-green-700">Pass</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="result" value="fail" class="text-red-600">
                            <span class="text-sm font-medium text-red-700">Fail</span>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Interviewer Notes (Optional)</label>
                    <textarea name="interviewer_notes" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                              placeholder="Add notes about the interview..."></textarea>
                </div>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">
                    <i class="fas fa-save mr-1"></i> Save Result
                </button>
            </form>
        </div>
        @endif

        {{-- Result Already Saved --}}
        @if($interview->result !== 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">
                <i class="fas fa-clipboard-check mr-1 text-green-500"></i> Interview Result
            </p>
            <div class="flex items-center gap-3">
                @if($interview->result === 'pass')
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                        <i class="fas fa-check-circle mr-1"></i> Passed
                    </span>
                @else
                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">
                        <i class="fas fa-times-circle mr-1"></i> Failed
                    </span>
                @endif
            </div>
            @if($interview->interviewer_notes)
                <p class="text-sm text-gray-600 mt-3 bg-gray-50 rounded-lg p-3">
                    {{ $interview->interviewer_notes }}
                </p>
            @endif
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">Status</p>
            @php
                $statusColors = [
                    'scheduled'   => 'bg-blue-100 text-blue-700',
                    'rescheduled' => 'bg-yellow-100 text-yellow-700',
                    'completed'   => 'bg-green-100 text-green-700',
                    'cancelled'   => 'bg-red-100 text-red-700',
                    'no_show'     => 'bg-gray-100 text-gray-600',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($interview->status) }}
            </span>
        </div>

        {{-- Cancel --}}
        @if($interview->status === 'scheduled')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">Cancel Interview</p>
            <form action="{{ route('admin.interviews.cancel', $interview->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this interview?')">
                @csrf
                <button type="submit"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-700 py-2 rounded-lg text-sm font-semibold">
                    <i class="fas fa-times mr-1"></i> Cancel Interview
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
