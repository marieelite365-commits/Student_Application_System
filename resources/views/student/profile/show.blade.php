@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user mr-2 text-blue-600"></i>My Profile
            </h1>
            <a href="{{ route('student.profile.edit') }}"
               class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-semibold transition">
                <i class="fas fa-user-edit mr-1"></i>Edit Profile
            </a>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">

        {{-- Photo + Name --}}
        <div class="flex items-center gap-5 mb-6 pb-6 border-b">
            @if($user->profile_photo_path)
                <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                     class="w-20 h-20 rounded-full object-cover border-4 border-blue-200">
            @else
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-400 text-3xl"></i>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                @if($profile)
                    <span class="inline-block mt-1 bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                        {{ $profile->student_id ?? 'Student' }}
                    </span>
                @endif
            </div>
        </div>

        @if($profile)

            {{-- Personal Info --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">CNIC</p>
                        <p class="text-sm font-medium text-gray-700">{{ $profile->cnic ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">Phone</p>
                        <p class="text-sm font-medium text-gray-700">{{ $profile->phone ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">Date of Birth</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $profile->date_of_birth?->format('d M Y') ?? '—' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">Gender</p>
                        <p class="text-sm font-medium text-gray-700 capitalize">{{ $profile->gender ?? '—' }}</p>
                    </div>

                </div>
            </div>

            {{-- Address --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Address
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="bg-gray-50 rounded p-3 md:col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Street Address</p>
                        <p class="text-sm font-medium text-gray-700">{{ $profile->address ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">City</p>
                        <p class="text-sm font-medium text-gray-700">{{ $profile->city ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 mb-1">Province</p>
                        <p class="text-sm font-medium text-gray-700">{{ $profile->province ?? '—' }}</p>
                    </div>

                </div>
            </div>

            {{-- Drive Folder --}}
            @if($profile->drive_folder_id)
            <div class="bg-blue-50 rounded p-4 flex items-center gap-3">
                <i class="fab fa-google-drive text-blue-500 text-xl"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-700">Google Drive Folder Linked</p>
                    <p class="text-xs text-blue-500">Your documents are synced to Google Drive</p>
                </div>
            </div>
            @endif

        @else

            {{-- No Profile --}}
            <div class="text-center py-8">
                <i class="fas fa-user-plus text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500 mb-4">Profile not created yet.</p>
                <a href="{{ route('student.profile.create') }}"
                   class="bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                    Create Profile
                </a>
            </div>

        @endif

    </div>

</div>

@endsection