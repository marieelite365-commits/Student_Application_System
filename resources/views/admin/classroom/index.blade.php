@extends('layouts.app')

@section('title', 'Google Classroom')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-chalkboard-teacher mr-2 text-teal-600"></i>Google Classroom
            </h1>
            <a href="{{ route('admin.admin.classroom.create') }}"
               class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded font-semibold text-sm">
                <i class="fas fa-plus mr-1"></i>Create Course
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

    {{-- Courses Grid --}}
    @forelse($courses as $course)
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $course->name }}</h2>
                    @if($course->section)
                        <p class="text-sm text-gray-500">Section: {{ $course->section }}</p>
                    @endif
                    @if($course->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $course->description }}</p>
                    @endif
                    <div class="flex gap-3 mt-3 flex-wrap">
                        <span class="text-xs bg-teal-100 text-teal-700 px-2 py-1 rounded-full">
                            <i class="fas fa-key mr-1"></i>Code: {{ $course->enrollment_code ?? 'N/A' }}
                        </span>
                        <span class="text-xs 
                            {{ $course->status === 'archived' ? 'bg-gray-100 text-gray-500' : 'bg-green-100 text-green-700' }} 
                            px-2 py-1 rounded-full">
                            {{ ucfirst($course->status ?? 'active') }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.admin.classroom.show', $course->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    @if($course->alternate_link)
                        <a href="{{ $course->alternate_link }}" target="_blank"
                           class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-external-link-alt mr-1"></i>Open
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-10 text-center">
            <i class="fas fa-chalkboard-teacher text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500 mb-4">No courses yet.</p>
            <a href="{{ route('admin.admin.classroom.create') }}"
               class="bg-teal-600 text-white px-6 py-2 rounded font-semibold">
                Create First Course
            </a>
        </div>
    @endforelse

</div>

@endsection