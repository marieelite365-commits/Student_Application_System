@extends('layouts.app')

@section('title', 'My Meetings')

@section('content')
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-video mr-2 text-blue-600"></i>My Meetings
        </h1>
        <p class="text-gray-500 text-sm mt-1">All your scheduled meetings</p>
    </div>

    {{-- Upcoming --}}
    <h2 class="text-lg font-semibold text-gray-700 mb-3">
        <i class="fas fa-clock mr-2 text-green-500"></i>Upcoming Meetings
    </h2>

    @php
        $upcoming = $meetings->filter(fn($m) => $m->start_time->isFuture());
        $past     = $meetings->filter(fn($m) => $m->end_time->isPast());
    @endphp

    @forelse($upcoming as $meeting)
        <div class="bg-white rounded-lg shadow p-5 mb-4 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $meeting->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $meeting->description }}</p>
                    <div class="flex gap-3 mt-3 flex-wrap">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            <i class="fas fa-tag mr-1"></i>{{ ucfirst($meeting->type) }}
                        </span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            <i class="fas fa-calendar mr-1"></i>{{ $meeting->start_time->format('d M Y') }}
                        </span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            <i class="fas fa-clock mr-1"></i>{{ $meeting->start_time->format('h:i A') }} — {{ $meeting->end_time->format('h:i A') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('student.meetings.join', $meeting->id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold text-sm flex-shrink-0">
                    <i class="fas fa-video mr-1"></i>Join Meet
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-400 mb-6">
            <i class="fas fa-video text-4xl mb-3"></i>
            <p>No upcoming meetings.</p>
        </div>
    @endforelse

    {{-- Past --}}
    <h2 class="text-lg font-semibold text-gray-700 mb-3 mt-6">
        <i class="fas fa-history mr-2 text-gray-500"></i>Past Meetings
    </h2>

    @forelse($past as $meeting)
        <div class="bg-white rounded-lg shadow p-5 mb-4 border-l-4 border-gray-300">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">{{ $meeting->title }}</h3>
                    <div class="flex gap-3 mt-2 flex-wrap">
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            {{ ucfirst($meeting->type) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>{{ $meeting->start_time->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>
                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-check mr-1"></i>Ended
                </span>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-400">
            <p>No past meetings yet.</p>
        </div>
    @endforelse

</div>

@endsection