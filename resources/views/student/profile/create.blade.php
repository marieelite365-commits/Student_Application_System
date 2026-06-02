@extends('layouts.app')

@section('title', 'Complete Profile')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Complete Your Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Fill in your details to activate your student account</p>
    </div>

    {{-- Progress indicator --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg px-5 py-3 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-700 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user text-white text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-800">Step 1 of 1 — Personal Information</p>
            <p class="text-xs text-blue-600">This information will be used for your student ID and official records</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Card top border --}}
        <div style="height:4px;background:linear-gradient(90deg,#1e3a8a,#3b5fc0);"></div>

        <form method="POST" action="{{ route('student.profile.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-6">

                {{-- ── Section 1: Personal Info ── --}}
                <div>
                    <h2 class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Full Name (read only from auth) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Full Name
                            </label>
                            <input type="text"
                                   value="{{ Auth::user()->name }}"
                                   disabled
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">From your registration</p>
                        </div>

                        {{-- Email (read only) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Email Address
                            </label>
                            <input type="text"
                                   value="{{ Auth::user()->email }}"
                                   disabled
                                   class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">From your registration</p>
                        </div>

                        {{-- CNIC --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                CNIC <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-id-badge"></i>
                                </span>
                                <input type="text"
                                       name="cnic"
                                       value="{{ old('cnic') }}"
                                       placeholder="3520212345678"
                                       maxlength="13"
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                       class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition
                                              focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('cnic') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">13 digits, no dashes</p>
                            @error('cnic')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       placeholder="03001234567"
                                       class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition
                                              focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('phone') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       max="{{ date('Y-m-d', strtotime('-5 years')) }}"
                                       class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition
                                              focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('date_of_birth') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            </div>
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-venus-mars"></i>
                                </span>
                                <select name="gender"
                                        class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition appearance-none
                                               focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                               {{ $errors->has('gender') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                    <option value="">-- Select Gender --</option>
                                    <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Divider --}}
                <hr class="border-gray-100">

                {{-- ── Section 2: Address ── --}}
                <div>
                    <h2 class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i> Address Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-400 text-sm">
                                    <i class="fas fa-home"></i>
                                </span>
                                <textarea name="address"
                                          rows="2"
                                          placeholder="House No., Street, Area"
                                          class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition resize-none
                                                 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                                 {{ $errors->has('address') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                City <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-city"></i>
                                </span>
                                <input type="text"
                                       name="city"
                                       value="{{ old('city') }}"
                                       placeholder="e.g. Lahore"
                                       class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition
                                              focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                              {{ $errors->has('city') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            </div>
                            @error('city')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Province --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Province <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="fas fa-map"></i>
                                </span>
                                <select name="province"
                                        class="w-full pl-9 pr-3 py-2.5 border rounded-lg text-sm transition appearance-none
                                               focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                               {{ $errors->has('province') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                    <option value="">-- Select Province --</option>
                                    <option value="Punjab"       {{ old('province') == 'Punjab'       ? 'selected' : '' }}>Punjab</option>
                                    <option value="Sindh"        {{ old('province') == 'Sindh'        ? 'selected' : '' }}>Sindh</option>
                                    <option value="KPK"          {{ old('province') == 'KPK'          ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
                                    <option value="Balochistan"  {{ old('province') == 'Balochistan'  ? 'selected' : '' }}>Balochistan</option>
                                    <option value="AJK"          {{ old('province') == 'AJK'          ? 'selected' : '' }}>Azad Jammu & Kashmir</option>
                                    <option value="Gilgit"       {{ old('province') == 'Gilgit'       ? 'selected' : '' }}>Gilgit-Baltistan</option>
                                    <option value="Islamabad"    {{ old('province') == 'Islamabad'    ? 'selected' : '' }}>Islamabad Capital Territory</option>
                                </select>
                            </div>
                            @error('province')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Divider --}}
                <hr class="border-gray-100">

                {{-- ── Section 3: Profile Photo ── --}}
                <div>
                    <h2 class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <i class="fas fa-camera"></i> Profile Photo <span class="text-gray-400 font-normal normal-case tracking-normal">(Optional)</span>
                    </h2>

                    <div class="flex items-start gap-4">
                        {{-- Preview --}}
                        <div id="photo-preview"
                             class="w-20 h-20 rounded-full bg-blue-100 border-2 border-blue-200 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <i class="fas fa-user text-blue-400 text-2xl"></i>
                        </div>

                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                                Upload Photo
                            </label>
                            <input type="file"
                                   name="profile_photo"
                                   id="profile_photo"
                                   accept="image/jpeg,image/png,image/jpg"
                                   onchange="previewPhoto(this)"
                                   class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg px-3 py-2
                                          file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                          file:text-xs file:font-semibold file:bg-blue-700 file:text-white
                                          hover:file:bg-blue-800 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">JPG or PNG, max 2MB</p>
                            @error('profile_photo')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Form Footer ── --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Your information is secure and encrypted
                </p>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold
                               rounded-lg text-sm transition shadow flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Profile
                </button>
            </div>

        </form>
    </div>

</div>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photo-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection