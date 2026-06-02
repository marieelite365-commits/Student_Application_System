@extends('layouts.app')

@section('title', 'Application Details')

@section('content')

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>Application Details
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">Application #{{ $application->id }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($application->status === 'approved') bg-green-100 text-green-700
                        @elseif($application->status === 'rejected') bg-red-100 text-red-700
                        @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-700
                        @elseif($application->status === 'submitted') bg-blue-100 text-blue-700
                        @elseif($application->status === 'enrolled') bg-purple-100 text-purple-700
                        @else bg-gray-100 text-gray-600
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                    <a href="{{ route('student.applications.index') }}"
                       class="text-gray-500 hover:text-gray-700 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Academic Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-graduation-cap mr-2 text-blue-600"></i>Academic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Degree Program</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->degree_program }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Department</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->department }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Semester</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->semester }} {{ $application->admission_year }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Submitted At</p>
                    <p class="font-medium text-gray-800 mt-0.5">
                        {{ $application->submitted_at ? $application->submitted_at->format('d M Y, h:i A') : '—' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Previous Education -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-school mr-2 text-blue-600"></i>Previous Education
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Last Qualification</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->last_qualification }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Institution</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->last_institution }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">CGPA / Percentage</p>
                    <p class="font-medium text-gray-800 mt-0.5">
                        {{ $application->last_cgpa ? $application->last_cgpa . ' CGPA' : '' }}
                        {{ $application->last_percentage ? $application->last_percentage . '%' : '' }}
                        {{ !$application->last_cgpa && !$application->last_percentage ? '—' : '' }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Passing Year</p>
                    <p class="font-medium text-gray-800 mt-0.5">{{ $application->passing_year }}</p>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                <i class="fas fa-folder-open mr-2 text-blue-600"></i>Uploaded Documents
            </h2>
            @if($application->documents->isEmpty())
                <p class="text-gray-400 text-sm">No documents uploaded.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($application->documents as $doc)
                        <div class="border rounded p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center">
                                    @if($doc->isImage())
                                        <i class="fas fa-image text-blue-500"></i>
                                    @else
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $doc->document_label }}</p>
                                    <p class="text-xs text-gray-400">{{ $doc->file_size_formatted }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($doc->is_verified)
                                    <span class="text-xs text-green-600">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                @endif
                                @if($doc->drive_file_url)
                                    <a href="{{ $doc->drive_file_url }}" target="_blank"
                                       class="text-blue-600 text-xs hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>Drive
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Admin Remarks -->
        @if($application->admin_remarks)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-comment-alt mr-2"></i>Admin Remarks
                </h2>
                <p class="text-yellow-700">{{ $application->admin_remarks }}</p>
            </div>
        @endif

        <!-- Actions -->
        @if($application->isDraft())
            <div class="bg-white rounded-lg shadow p-6 flex justify-end">
                <form method="POST"
                      action="{{ route('student.applications.submit', $application->id) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Submit this application? You cannot edit it after submission.')"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded font-semibold transition">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Application
                    </button>
                </form>
            </div>
        @endif

    </div>

@endsection