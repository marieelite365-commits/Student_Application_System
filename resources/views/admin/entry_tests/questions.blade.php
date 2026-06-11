@extends('layouts.app')
@section('title', 'Manage Questions')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-question-circle text-white text-xs"></i>
            </span>
            Manage Questions
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">Entry test question bank</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.entry_tests.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        <a href="{{ route('admin.entry_tests.questions.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
            <i class="fas fa-plus mr-1"></i> Add Question
        </a>
    </div>
</div>

{{-- Success --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Question</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Category</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Correct Answer</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($questions as $question)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ Str::limit($question->question, 60) }}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            A: {{ Str::limit($question->option_a, 20) }} |
                            B: {{ Str::limit($question->option_b, 20) }} |
                            C: {{ Str::limit($question->option_c, 20) }} |
                            D: {{ Str::limit($question->option_d, 20) }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $question->category }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold uppercase">
                            {{ $question->correct_answer }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($question->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.entry_tests.questions.delete', $question->id) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this question?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        <i class="fas fa-question-circle text-3xl mb-2 block"></i>
                        No questions added yet.
                        <a href="{{ route('admin.entry_tests.questions.create') }}"
                           class="block mt-2 text-green-600 hover:underline text-sm">Add first question</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($questions->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $questions->links() }}
        </div>
    @endif
</div>

@endsection