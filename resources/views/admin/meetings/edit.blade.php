@extends('layouts.app')

@section('title', 'Edit Meeting')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-edit mr-2 text-blue-600"></i>Edit Meeting
            </h1>
            <a href="{{ route('admin.meetings.index') }}"
               class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.meetings.update', $meeting->id) }}"
          class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title"
                   value="{{ old('title', $meeting->title) }}"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-400 @enderror">
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $meeting->description) }}</textarea>
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Type <span class="text-red-500">*</span>
            </label>
            <select name="type"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="interview"   {{ old('type', $meeting->type) === 'interview'   ? 'selected' : '' }}>Interview</option>
                <option value="orientation" {{ old('type', $meeting->type) === 'orientation' ? 'selected' : '' }}>Orientation</option>
                <option value="class"       {{ old('type', $meeting->type) === 'class'       ? 'selected' : '' }}>Class</option>
                <option value="general"     {{ old('type', $meeting->type) === 'general'     ? 'selected' : '' }}>General</option>
            </select>
        </div>

        {{-- Date Time --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Start Time <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="start_time"
                       value="{{ old('start_time', $meeting->start_time->format('Y-m-d\TH:i')) }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('start_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    End Time <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="end_time"
                       value="{{ old('end_time', $meeting->end_time->format('Y-m-d\TH:i')) }}"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('end_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="scheduled"  {{ old('status', $meeting->status) === 'scheduled'  ? 'selected' : '' }}>Scheduled</option>
                <option value="cancelled"  {{ old('status', $meeting->status) === 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
                <option value="completed"  {{ old('status', $meeting->status) === 'completed'  ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        {{-- Meet Link --}}
        <div class="bg-blue-50 rounded p-4">
            <p class="text-xs text-gray-500 mb-1">Current Meet Link</p>
            <p class="text-sm text-blue-700 font-medium">{{ $meeting->meet_link }}</p>
        </div>

        {{-- Submit --}}
        <div class="flex justify-between items-center pt-4 border-t">
            <a href="{{ route('admin.meetings.show', $meeting->id) }}"
               class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-times mr-1"></i>Cancel
            </a>
            <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded font-semibold transition">
                <i class="fas fa-save mr-2"></i>Update Meeting
            </button>
        </div>

    </form>
</div>

@endsection