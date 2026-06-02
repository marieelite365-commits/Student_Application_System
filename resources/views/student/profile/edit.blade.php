@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-edit mr-2 text-blue-600"></i>Edit Profile
                </h1>
                <a href="{{ route('student.profile.show') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('student.profile.update') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Photo -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-camera mr-2 text-blue-600"></i>Profile Photo
                </h2>
                <div class="flex items-center gap-4">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                             class="w-16 h-16 rounded-full object-cover border-4 border-blue-200">
                    @else
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-400 text-2xl"></i>
                        </div>
                    @endif
                    <input type="file" name="profile_photo" accept="image/*"
                           class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                  file:rounded file:border-0 file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                </div>
                <p class="text-xs text-gray-400 mt-1">Optional. Max 2MB. JPG/PNG only.</p>
            </div>

            <!-- Personal Info -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>Personal Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- CNIC (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                        <input type="text" value="{{ $profile->cnic }}" disabled
                               class="w-full border rounded px-3 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">CNIC cannot be changed.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"
                               placeholder="03001234567"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth"
                               value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-400 @enderror">
                        @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gender') border-red-400 @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $profile->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Address -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Address
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address"
                               value="{{ old('address', $profile->address) }}"
                               placeholder="House #, Street, Area"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror">
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city"
                               value="{{ old('city', $profile->city) }}"
                               placeholder="Lahore"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-400 @enderror">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                        <select name="province"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('province') border-red-400 @enderror">
                            <option value="">Select Province</option>
                            <option value="Punjab" {{ old('province', $profile->province) === 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Sindh" {{ old('province', $profile->province) === 'Sindh' ? 'selected' : '' }}>Sindh</option>
                            <option value="KPK" {{ old('province', $profile->province) === 'KPK' ? 'selected' : '' }}>KPK</option>
                            <option value="Balochistan" {{ old('province', $profile->province) === 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
                            <option value="AJK" {{ old('province', $profile->province) === 'AJK' ? 'selected' : '' }}>AJK</option>
                            <option value="Gilgit-Baltistan" {{ old('province', $profile->province) === 'Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                        </select>
                        @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-between items-center pt-4 border-t">
                <a href="{{ route('student.profile.show') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-times mr-1"></i>Cancel
                </a>
                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded font-semibold transition">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </div>

        </form>
    </div>

@endsection