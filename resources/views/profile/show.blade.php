@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user mr-2 text-blue-600"></i>My Profile
                </h1>
                <a href="{{ route('student.profile.edit') }}"
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm transition">
                    <i class="fas fa-edit mr-1"></i>Edit Profile
                </a>
            </div>
        </div>

        @if(!$profile)
            <!-- No Profile Yet -->
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400">
                <i class="fas fa-user-slash text-5xl mb-4"></i>
                <p class="text-lg">Profile not completed yet.</p>
                <a href="{{ route('student.profile.create') }}"
                   class="mt-4 inline-block bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 transition">
                    Complete Profile
                </a>
            </div>
        @else

            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center gap-6 mb-6">

                    <!-- Photo -->
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-blue-200">
                    @else
                        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-400 text-4xl"></i>
                        </div>
                    @endif

                    <!-- Name & ID -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-blue-700 font-semibold">{{ $profile->student_id }}</p>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">
                            Active Student
                        </span>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">CNIC</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $profile->cnic }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Phone</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $profile->phone ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Date of Birth</p>
                        <p class="font-medium text-gray-800 mt-0.5">
                            {{ $profile->date_of_birth?->format('d M Y') ?? '—' }}
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Gender</p>
                        <p class="font-medium text-gray-800 mt-0.5 capitalize">{{ $profile->gender ?? '—' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded p-3 md:col-span-2">
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Address</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $profile->full_address ?: '—' }}</p>
                    </div>

                </div>
            </div>

            <!-- 2FA Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-shield-alt mr-2 text-blue-600"></i>Security
                </h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Two Factor Authentication</p>
                        <p class="text-sm text-gray-500">Extra security for your account</p>
                    </div>
                    @if($user->hasTwoFactorEnabled())
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            <i class="fas fa-check mr-1"></i>Enabled
                        </span>
                    @else
                        <a href="/user/two-factor-authentication"
                           class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold hover:bg-yellow-200 transition">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Not Enabled
                        </a>
                    @endif
                </div>
            </div>

        @endif
    </div>

@endsection