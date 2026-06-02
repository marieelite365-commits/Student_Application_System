@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-plus mr-2 text-blue-600"></i>Complete Your Profile
            </h1>
            <p class="text-gray-500 mt-1">Please fill in your personal details to continue.</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('student.profile.store') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf

            <!-- Profile Photo -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-camera mr-2 text-blue-600"></i>Profile Photo
                </h2>
                <input type="file" name="profile_photo" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded file:border-0 file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Optional. Max 2MB. JPG/PNG only.</p>
            </div>

            <!-- Personal Info -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>Personal Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNIC <span class="text-red-500">*</span></label>
                        <input type="text" name="cnic" value="{{ old('cnic') }}"
                               placeholder="3520212345678" maxlength="13"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cnic') border-red-400 @enderror">
                        @error('cnic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="03001234567"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_of_birth') border-red-400 @enderror">
                        @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gender') border-red-400 @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
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
                        <input type="text" name="address" value="{{ old('address') }}"
                               placeholder="House #, Street, Area"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror">
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               placeholder="Lahore"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-400 @enderror">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                        <select name="province"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('province') border-red-400 @enderror">
                            <option value="">Select Province</option>
                            <option value="Punjab" {{ old('province') === 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Sindh" {{ old('province') === 'Sindh' ? 'selected' : '' }}>Sindh</option>
                            <option value="KPK" {{ old('province') === 'KPK' ? 'selected' : '' }}>KPK</option>
                            <option value="Balochistan" {{ old('province') === 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
                            <option value="AJK" {{ old('province') === 'AJK' ? 'selected' : '' }}>AJK</option>
                            <option value="Gilgit-Baltistan" {{ old('province') === 'Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                        </select>
                        @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded font-semibold transition">
                    <i class="fas fa-save mr-2"></i>Save Profile
                </button>
            </div>

        </form>
    </div>

@endsection