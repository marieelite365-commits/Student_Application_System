@extends('layouts.app')

@section('title', 'My Applications')

@section('content')

    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>My Applications
                </h1>
                <a href="{{ route('student.applications.create') }}"
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm transition">
                    <i class="fas fa-plus mr-1"></i>New Application
                </a>
            </div>
        </div>

        <!-- Applications List -->
        <div class="bg-white rounded-lg shadow">
            @if($applications->isEmpty())
                <div class="p-10 text-center text-gray-400">
                    <i class="fas fa-folder-open text-5xl mb-4"></i>
                    <p class="text-lg">No applications yet.</p>
                    <a href="{{ route('student.applications.create') }}"
                       class="mt-4 inline-block bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 transition">
                        Apply Now
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Program</th>
                                <th class="px-6 py-3 text-left">Department</th>
                                <th class="px-6 py-3 text-left">Semester</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Submitted</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3 font-medium">{{ $application->degree_program }}</td>
                                    <td class="px-6 py-3">{{ $application->department }}</td>
                                    <td class="px-6 py-3">{{ $application->semester }} {{ $application->admission_year }}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($application->status === 'approved') bg-green-100 text-green-700
                                            @elseif($application->status === 'rejected') bg-red-100 text-red-700
                                            @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-700
                                            @elseif($application->status === 'submitted') bg-blue-100 text-blue-700
                                            @elseif($application->status === 'enrolled') bg-purple-100 text-purple-700
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-500">
                                        {{ $application->submitted_at
                                            ? $application->submitted_at->format('d M Y')
                                            : '—' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('student.applications.show', $application->id) }}"
                                           class="text-blue-600 hover:underline">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection