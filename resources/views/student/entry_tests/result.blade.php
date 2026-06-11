@extends('layouts.app')
@section('title', 'Test Result')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-clipboard-check text-white text-xs"></i>
            </span>
            Test Result
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">{{ $entryTest->application->degree_program }}</p>
    </div>
    <a href="{{ route('student.dashboard') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-home mr-1"></i> Dashboard
    </a>
</div>

{{-- Result Card --}}
<div class="max-w-lg mx-auto">

    @if($entryTest->result === 'pass')
        <div class="bg-white rounded-xl shadow-sm border border-green-200 p-8 text-center mb-5">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-500 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-green-700 mb-1">Congratulations!</h2>
            <p class="text-green-600 mb-4">You have passed the entry test!</p>

            <div class="bg-green-50 rounded-lg p-4 mb-4">
                <p class="text-4xl font-bold text-green-700">
                    {{ $entryTest->obtained_marks }}/{{ $entryTest->total_marks }}
                </p>
                <p class="text-sm text-green-600 mt-1">Your Score</p>
            </div>

            <p class="text-sm text-gray-500">
                Your admission application has been <strong class="text-green-600">approved</strong>.
                You will be contacted shortly with further details.
            </p>
        </div>

    @else
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-8 text-center mb-5">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-red-500 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-red-700 mb-1">Better Luck Next Time</h2>
            <p class="text-red-500 mb-4">You did not pass the entry test.</p>

            <div class="bg-red-50 rounded-lg p-4 mb-4">
                <p class="text-4xl font-bold text-red-600">
                    {{ $entryTest->obtained_marks }}/{{ $entryTest->total_marks }}
                </p>
                <p class="text-sm text-red-500 mt-1">Your Score</p>
            </div>

            <p class="text-sm text-gray-500">
                Passing marks were <strong>{{ $entryTest->passing_marks }}/{{ $entryTest->total_marks }}</strong>.
                Unfortunately your application could not be approved at this time.
            </p>
        </div>
    @endif

    {{-- Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
        <p class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">
            <i class="fas fa-chart-bar mr-1 text-blue-500"></i> Test Summary
        </p>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-2xl font-bold text-green-600">
                    {{ $entryTest->answers->where('is_correct', true)->count() }}
                </p>
                <p class="text-xs text-gray-400">Correct</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-500">
                    {{ $entryTest->answers->where('is_correct', false)->count() }}
                </p>
                <p class="text-xs text-gray-400">Wrong</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-600">
                    {{ $entryTest->total_marks - $entryTest->answers->count() }}
                </p>
                <p class="text-xs text-gray-400">Skipped</p>
            </div>
        </div>
    </div>

    {{-- Answers Review --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm font-semibold text-gray-700 mb-4 border-b pb-2">
            <i class="fas fa-list mr-1 text-gray-500"></i> Answers Review
        </p>
        <div class="space-y-3">
            @foreach($entryTest->answers as $index => $answer)
                <div class="bg-gray-50 rounded-lg p-3 {{ $answer->is_correct ? 'border-l-4 border-green-500' : 'border-l-4 border-red-400' }}">
                    <p class="text-sm font-medium text-gray-800 mb-2">
                        {{ $index + 1 }}. {{ $answer->question->question }}
                    </p>
                    <div class="flex gap-3 text-xs">
                        <span class="text-gray-500">
                            Your answer: <strong class="uppercase {{ $answer->is_correct ? 'text-green-600' : 'text-red-500' }}">
                                {{ $answer->selected_answer ?? '—' }}
                            </strong>
                        </span>
                        @if(!$answer->is_correct)
                            <span class="text-gray-500">
                                Correct: <strong class="uppercase text-green-600">{{ $answer->question->correct_answer }}</strong>
                            </span>
                        @endif
                        @if($answer->is_correct)
                            <span class="ml-auto text-green-600 font-semibold">✓</span>
                        @else
                            <span class="ml-auto text-red-500 font-semibold">✗</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection