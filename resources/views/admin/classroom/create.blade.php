@extends('layouts.app')

@section('title', 'Add Course')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-chalkboard-teacher mr-2 text-teal-600"></i>Add Course
            </h1>
            <a href="{{ route('admin.admin.classroom.index') }}"
               class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-teal-50 border border-teal-200 rounded p-4 mb-6">
        <p class="text-sm text-teal-700 font-semibold mb-2">
            <i class="fas fa-info-circle mr-2"></i>How to add a course:
        </p>
        <ol class="text-sm text-teal-600 space-y-1 list-decimal list-inside">
            <li>Go to <a href="https://classroom.google.com" target="_blank" class="underline font-semibold">classroom.google.com</a></li>
            <li>Create a new course</li>
            <li>Copy the course link and enrollment code</li>
            <li>Paste them below</li>
        </ol>
    </div>

    {{-- Error --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.admin.classroom.store') }}"
          class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf

        {{-- Course Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Course Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. Computer Science 101"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Section --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
            <input type="text" name="section" value="{{ old('section') }}"
                   placeholder="e.g. Section A"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                      placeholder="Course description..."
                      class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
        </div>

        {{-- Classroom Link --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Google Classroom Link <span class="text-red-500">*</span>
            </label>
            <input type="url" name="alternate_link" value="{{ old('alternate_link') }}"
                   placeholder="https://classroom.google.com/c/xxxxx"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('alternate_link') border-red-400 @enderror">
            @error('alternate_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Enrollment Code --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Enrollment Code
            </label>
            <input type="text" name="enrollment_code" value="{{ old('enrollment_code') }}"
                   placeholder="e.g. abc123"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <p class="text-xs text-gray-400 mt-1">Students will use this code to join the course.</p>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end pt-4 border-t">
            <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded font-semibold transition">
                <i class="fas fa-plus mr-2"></i>Add Course
            </button>
        </div>

    </form>
</div>

@endsection