@extends('layouts.app')
@section('title', 'Test Result Detail')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-white text-xs"></i>
            </span>
            Test Result Detail
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">{{ $entryTest->student->name }}</p>
    </div>
    <a href="{{ route('admin.entry_tests.index') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Main --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Student Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-user mr-1 text-green-500"></i> Student Information
            </p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Student Name</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->student->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Email</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->student->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Degree Program</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->application->degree_program }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Test Date</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->completed_at?->format('d M Y, h:i A') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Answers Detail --}}
        @if($entryTest->answers->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-list mr-1 text-green-500"></i> Question & Answers
            </p>
            <div class="space-y-4">
                @foreach($entryTest->answers as $index => $answer)
                    <div class="bg-gray-50 rounded-lg p-4 {{ $answer->is_correct ? 'border-l-4 border-green-500' : 'border-l-4 border-red-400' }}">
                        <p class="text-sm font-medium text-gray-800 mb-2">
                            {{ $index + 1 }}. {{ $answer->question->question }}
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                            @foreach(['a', 'b', 'c', 'd'] as $opt)
                                <div class="px-2 py-1 rounded
                                    {{ $answer->question->correct_answer === $opt ? 'bg-green-100 text-green-700 font-semibold' : '' }}
                                    {{ $answer->selected_answer === $opt && !$answer->is_correct ? 'bg-red-100 text-red-700 font-semibold' : '' }}
                                    {{ $answer->selected_answer !== $opt && $answer->question->correct_answer !== $opt ? 'text-gray-600' : '' }}">
                                    {{ strtoupper($opt) }}: {{ $answer->question->{'option_' . $opt} }}
                                </div>
                            @endforeach
                        </div>
                        <div class="flex gap-2 text-xs">
                            <span class="text-gray-500">Selected: <strong class="uppercase">{{ $answer->selected_answer ?? '—' }}</strong></span>
                            <span class="text-gray-500">|</span>
                            <span class="text-gray-500">Correct: <strong class="uppercase text-green-600">{{ $answer->question->correct_answer }}</strong></span>
                            @if($answer->is_correct)
                                <span class="ml-auto text-green-600 font-semibold">✓ Correct</span>
                            @else
                                <span class="ml-auto text-red-500 font-semibold">✗ Wrong</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Result Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            @if($entryTest->result === 'pass')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <p class="font-bold text-green-700 text-lg">Passed</p>
            @elseif($entryTest->result === 'fail')
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-times text-red-500 text-2xl"></i>
                </div>
                <p class="font-bold text-red-700 text-lg">Failed</p>
            @else
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                </div>
                <p class="font-bold text-yellow-700 text-lg">Pending</p>
            @endif

            <div class="mt-4 bg-gray-50 rounded-lg p-3">
                <p class="text-3xl font-bold text-gray-800">
                    {{ $entryTest->obtained_marks ?? '—' }}/{{ $entryTest->total_marks }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Passing: {{ $entryTest->passing_marks }}/{{ $entryTest->total_marks }}</p>
            </div>
        </div>

        {{-- Timing --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm font-semibold text-gray-700 mb-3">Timing</p>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Started</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->started_at?->format('h:i A') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Completed</p>
                    <p class="font-medium text-gray-800">{{ $entryTest->completed_at?->format('h:i A') ?? '—' }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection