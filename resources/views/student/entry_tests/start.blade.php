@extends('layouts.app')
@section('title', 'Entry Test')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-white text-xs"></i>
            </span>
            Entry Test
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">{{ $entryTest->application->degree_program }}</p>
    </div>
    {{-- Timer --}}
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-2 text-center">
        <p class="text-xs text-red-500 font-semibold">Time Remaining</p>
        <p class="text-xl font-bold text-red-600" id="timer">20:00</p>
    </div>
</div>

{{-- Warning --}}
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-5 text-sm">
    <i class="fas fa-exclamation-triangle mr-1"></i>
    <strong>Important:</strong> Do not refresh or close this page. Test has {{ $questions->count() }} questions, 
    each 1 mark. Passing marks: {{ $entryTest->passing_marks }}/{{ $entryTest->total_marks }}.
</div>

{{-- Test Form --}}
<form action="{{ route('student.entry_tests.submit', $entryTest->id) }}" method="POST" id="test-form">
    @csrf

    <div class="space-y-5">
        @foreach($questions as $index => $question)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="font-semibold text-gray-800 mb-4">
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-sm mr-2">Q{{ $index + 1 }}</span>
                    {{ $question->question }}
                </p>
                <div class="space-y-2">
                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-green-50 hover:border-green-200 cursor-pointer transition">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $opt }}"
                                   class="text-green-600">
                            <span class="text-sm text-gray-700">
                                <span class="font-semibold uppercase text-green-600 mr-1">{{ $opt }}.</span>
                                {{ $question->{'option_' . $opt} }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Submit Button --}}
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
            Make sure you have answered all questions before submitting.
        </p>
        <button type="submit"
                onclick="return confirm('Are you sure you want to submit the test?')"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-lg font-semibold text-sm">
            <i class="fas fa-paper-plane mr-2"></i> Submit Test
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
// ─── Timer ────────────────────────────────────────────────────
let timeLeft = 20 * 60; // 20 minutes

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer').textContent =
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

    if (timeLeft <= 60) {
        document.getElementById('timer').classList.add('text-red-700');
    }

    if (timeLeft <= 0) {
        document.getElementById('test-form').submit();
    }

    timeLeft--;
}

updateTimer();
setInterval(updateTimer, 1000);
</script>
@endpush