@extends('layouts.app')
@section('title', 'Add Question')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus text-white text-xs"></i>
            </span>
            Add Question
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">Add new entry test question</p>
    </div>
    <a href="{{ route('admin.entry_tests.questions') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.entry_tests.questions.store') }}" method="POST">
        @csrf

        {{-- Category --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Program / Category <span class="text-red-500">*</span>
            </label>
            <select name="category"
                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
               <option value="">-- Select Program --</option>
               <option value="BS">BS</option>
               <option value="MS">MS</option>
               <option value="PhD">PhD</option>
               <option value="Associate Degree">Associate Degree</option>
            </select>
        </div>

        {{-- Question --}}
        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Question <span class="text-red-500">*</span>
            </label>
            <textarea name="question" rows="3"
                      placeholder="Write your question here..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">{{ old('question') }}</textarea>
            @error('question')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Options --}}
        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Option A <span class="text-red-500">*</span>
                </label>
                <input type="text" name="option_a"
                       value="{{ old('option_a') }}"
                       placeholder="Option A"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Option B <span class="text-red-500">*</span>
                </label>
                <input type="text" name="option_b"
                       value="{{ old('option_b') }}"
                       placeholder="Option B"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Option C <span class="text-red-500">*</span>
                </label>
                <input type="text" name="option_c"
                       value="{{ old('option_c') }}"
                       placeholder="Option C"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Option D <span class="text-red-500">*</span>
                </label>
                <input type="text" name="option_d"
                       value="{{ old('option_d') }}"
                       placeholder="Option D"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>
        </div>

        {{-- Correct Answer --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Correct Answer <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-4">
                @foreach(['a', 'b', 'c', 'd'] as $option)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="correct_answer" value="{{ $option }}"
                               {{ old('correct_answer') === $option ? 'checked' : '' }}
                               class="text-green-600">
                        <span class="text-sm font-semibold uppercase text-gray-700">{{ $option }}</span>
                    </label>
                @endforeach
            </div>
            @error('correct_answer')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold text-sm">
            <i class="fas fa-save mr-2"></i> Save Question
        </button>
    </form>
</div>

@endsection