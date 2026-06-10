@extends('layouts.app')

@section('title', 'Schedule Meeting')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-video mr-2 text-blue-600"></i>Schedule Meeting
            </h1>
            <a href="{{ route('admin.meetings.index') }}"
               class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.meetings.store') }}"
          class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. Admission Interview 2026"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-400 @enderror">
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                      placeholder="Meeting details..."
                      class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Type <span class="text-red-500">*</span>
            </label>
            <select name="type"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-400 @enderror">
                <option value="">Select Type</option>
                <option value="interview"    {{ old('type') === 'interview'    ? 'selected' : '' }}>Interview</option>
                <option value="orientation"  {{ old('type') === 'orientation'  ? 'selected' : '' }}>Orientation</option>
                <option value="class"        {{ old('type') === 'class'        ? 'selected' : '' }}>Class</option>
                <option value="general"      {{ old('type') === 'general'      ? 'selected' : '' }}>General</option>
            </select>
            @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Meet Link --}}
        <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
        Google Meet Link <span class="text-red-500">*</span>
        </label>
        <input type="url" name="meet_link"
           value="{{ old('meet_link') }}"
           placeholder="https://meet.google.com/xxx-yyyy-zzz"
           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meet_link') border-red-400 @enderror">
        <p class="text-xs text-gray-400 mt-1">
        <a href="https://meet.google.com" target="_blank" class="text-blue-500 underline">
            Click here
        </a> to create a Google Meet, then paste the link above.
        </p>
        @error('meet_link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Date Time --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Start Time <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-400 @enderror">
                @error('start_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    End Time <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-400 @enderror">
                @error('end_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- For --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Send To <span class="text-red-500">*</span>
            </label>
            <select name="for" id="forSelect"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="toggleStudents()">
                <option value="all"      {{ old('for') === 'all'      ? 'selected' : '' }}>All Students</option>
                <option value="specific" {{ old('for') === 'specific' ? 'selected' : '' }}>Specific Students</option>
            </select>
        </div>

        {{-- Specific Students --}}
        <div id="studentsDiv" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Select Students <span class="text-red-500">*</span>
            </label>
            <div class="border rounded p-3 max-h-48 overflow-y-auto space-y-2">
                @foreach($students as $student)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="students[]" value="{{ $student->id }}"
                               {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}
                               class="rounded">
                        <span>{{ $student->name }}</span>
                        <span class="text-gray-400 text-xs">{{ $student->email }}</span>
                    </label>
                @endforeach
            </div>
            @error('students')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end pt-4 border-t">
            <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded font-semibold transition">
                <i class="fas fa-calendar-plus mr-2"></i>Schedule Meeting
            </button>
        </div>

    </form>
</div>

<script>
    function toggleStudents() {
        const val = document.getElementById('forSelect').value;
        const div = document.getElementById('studentsDiv');
        div.classList.toggle('hidden', val !== 'specific');
    }

    // On page load
    toggleStudents();
</script>

@endsection