@extends('layouts.app')

@section('title', 'Course Details')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-chalkboard-teacher mr-2 text-teal-600"></i>{{ $course->name }}
            </h1>
            <a href="{{ route('admin.admin.classroom.index') }}"
               class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>
    </div>

    {{-- Success/Error --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Course Info --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            Course Information
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Course Name</p>
                <p class="font-medium text-gray-800">{{ $course->name }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Section</p>
                <p class="font-medium text-gray-800">{{ $course->section ?? '—' }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Enrollment Code</p>
                <p class="font-bold text-teal-700 text-lg">{{ $course->enrollment_code ?? '—' }}</p>
            </div>

            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $course->status === 'archived' ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-700' }}">
                    {{ ucfirst($course->status ?? 'active') }}
                </span>
            </div>

            @if($course->description)
            <div class="bg-gray-50 rounded p-3 md:col-span-2">
                <p class="text-xs text-gray-400 mb-1">Description</p>
                <p class="text-sm text-gray-700">{{ $course->description }}</p>
            </div>
            @endif

        </div>

        {{-- Open in Classroom --}}
        @if($course->alternate_link)
        <div class="mt-4 bg-teal-50 rounded p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-chalkboard-teacher text-teal-500 text-xl"></i>
                <div>
                    <p class="text-sm font-semibold text-teal-700">Google Classroom</p>
                    <p class="text-xs text-teal-500">Open course in Google Classroom</p>
                </div>
            </div>
            <a href="{{ $course->alternate_link }}" target="_blank"
               class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-semibold">
                <i class="fas fa-external-link-alt mr-1"></i>Open
            </a>
        </div>
        @endif
    </div>

    {{-- Post Announcement --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-bullhorn mr-2 text-teal-600"></i>Post Announcement
        </h2>
        <form method="POST" action="{{ route('admin.admin.classroom.announce', $course->id) }}">
            @csrf
            <textarea name="announcement" rows="3"
                      placeholder="Write announcement for students..."
                      class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 mb-3"></textarea>
            <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm font-semibold">
                <i class="fas fa-paper-plane mr-1"></i>Post Announcement
            </button>
        </form>
    </div>

    {{-- Enroll Student --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-user-plus mr-2 text-teal-600"></i>Enroll Student
        </h2>
        <form method="POST" action="{{ route('admin.admin.classroom.enroll', $course->id) }}">
            @csrf
            <div class="flex gap-3">
                <input type="email" name="email"
                       placeholder="Student email address"
                       class="flex-1 border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-semibold">
                    <i class="fas fa-user