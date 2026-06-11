@extends('layouts.app')

@section('title', 'Application #' . $application->id)

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.applications.index') }}"
                   class="text-sm text-gray-500 hover:text-blue-600 mb-1 inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Applications
                </a>
                <h1 class="text-2xl font-bold text-gray-800 mt-1">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>Application #{{ $application->id }}
                </h1>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                @if($application->status === 'approved') bg-green-100 text-green-700
                @elseif($application->status === 'rejected') bg-red-100 text-red-700
                @elseif($application->status === 'under_review') bg-yellow-100 text-yellow-700
                @elseif($application->status === 'submitted') bg-blue-100 text-blue-700
                @elseif($application->status === 'enrolled') bg-purple-100 text-purple-700
                @else bg-gray-100 text-gray-600
                @endif">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </div>
    </div>

    {{-- Student Info + Application Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Student Info --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-user-circle text-blue-600"></i> Student Information
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Name</span>
                    <span class="font-medium text-gray-800">{{ $application->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-800">{{ $application->user->email ?? 'N/A' }}</span>
                </div>
                @if($application->studentProfile)
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Student ID</span>
                    <span class="font-medium text-gray-800">{{ $application->studentProfile->student_id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">CNIC</span>
                    <span class="font-medium text-gray-800">{{ $application->studentProfile->cnic ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Phone</span>
                    <span class="font-medium text-gray-800">{{ $application->studentProfile->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Address</span>
                    <span class="font-medium text-gray-800">{{ $application->studentProfile->address ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Application Details --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i> Application Details
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Degree Program</span>
                    <span class="font-medium text-gray-800">{{ $application->degree_program }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Department</span>
                    <span class="font-medium text-gray-800">{{ $application->department }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Semester</span>
                    <span class="font-medium text-gray-800">{{ $application->semester }} {{ $application->admission_year }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Last Qualification</span>
                    <span class="font-medium text-gray-800">{{ $application->last_qualification }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Institution</span>
                    <span class="font-medium text-gray-800">{{ $application->last_institution }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">CGPA / Percentage</span>
                    <span class="font-medium text-gray-800">
                        {{ $application->last_cgpa ?? '—' }}
                        @if($application->last_percentage) / {{ $application->last_percentage }}% @endif
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Passing Year</span>
                    <span class="font-medium text-gray-800">{{ $application->passing_year }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Submitted At</span>
                    <span class="font-medium text-gray-800">
                        {{ $application->submitted_at ? $application->submitted_at->format('d M Y, h:i A') : '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Documents --}}
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-700 flex items-center gap-2">
                <i class="fas fa-folder-open text-yellow-500"></i> Uploaded Documents
            </h2>
            <span class="bg-blue-50 text-blue-700 px-3 py-0.5 rounded-full text-xs font-semibold">
                {{ $application->documents->count() }} files
            </span>
        </div>
        <div class="overflow-x-auto">
            @if($application->documents->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Document Type</th>
                        <th class="px-6 py-3 text-left">File Name</th>
                        <th class="px-6 py-3 text-left">Size</th>
                        <th class="px-6 py-3 text-left">Google Drive</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($application->documents as $index => $doc)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1 font-medium text-gray-700">
                                @php
                                    $icons = [
                                        'profile_photo'      => 'fa-portrait',
                                        'cnic_front'         => 'fa-id-card',
                                        'cnic_back'          => 'fa-id-card',
                                        'matric_certificate' => 'fa-graduation-cap',
                                        'matric_marksheet'   => 'fa-file-alt',
                                        'inter_certificate'  => 'fa-graduation-cap',
                                        'inter_marksheet'    => 'fa-file-alt',
                                        'domicile'           => 'fa-map-marker-alt',
                                    ];
                                    $icon = $icons[$doc->document_type] ?? 'fa-file';
                                @endphp
                                <i class="fas {{ $icon }} text-blue-400"></i>
                                {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $doc->original_filename }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ number_format($doc->file_size / 1024, 1) }} KB</td>
                        <td class="px-6 py-3">
                            @if($doc->drive_file_url)
                                <a href="{{ $doc->drive_file_url }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-blue-600 hover:underline text-xs">
                                    <i class="fab fa-google-drive"></i> View on Drive
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Not uploaded to Drive</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="px-6 py-10 text-center text-gray-400">
                    <i class="fas fa-folder-open text-3xl mb-2"></i>
                    <p>No documents uploaded yet.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Drive Folder + Update Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Drive Folder --}}
        @if($application->drive_folder_id)
        <div class="bg-white rounded-lg shadow p-6 flex items-center gap-4">
            <div class="bg-yellow-50 rounded-full p-3">
                <i class="fab fa-google-drive text-yellow-500 text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Google Drive Folder</p>
                <p class="text-xs text-gray-400 mb-2">Student's documents folder</p>
                <a href="https://drive.google.com/drive/folders/{{ $application->drive_folder_id }}"
                   target="_blank"
                   class="inline-flex items-center gap-1 text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded transition">
                    <i class="fas fa-external-link-alt"></i> Open on Drive
                </a>
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        <div class="bg-white rounded-lg shadow p-6 {{ $application->drive_folder_id ? '' : 'md:col-span-2' }}">
            <h2 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-cog text-gray-500"></i> Update Status
            </h2>
            <form action="{{ route('admin.applications.status', $application->id) }}" method="POST">
                @csrf
                <div class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status</label>
                        <select name="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="under_review" {{ $application->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="approved"     {{ $application->status === 'approved'     ? 'selected' : '' }}>Approved</option>
                            <option value="rejected"     {{ $application->status === 'rejected'     ? 'selected' : '' }}>Rejected</option>
                            <option value="enrolled"     {{ $application->status === 'enrolled'     ? 'selected' : '' }}>Enrolled</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-48">
                        <label class="block text-xs text-gray-500 mb-1">Admin Remarks</label>
                        <input type="text" name="admin_remarks"
                               value="{{ $application->admin_remarks }}"
                               placeholder="Optional remarks..."
                               class="border rounded px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                            class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm transition">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection