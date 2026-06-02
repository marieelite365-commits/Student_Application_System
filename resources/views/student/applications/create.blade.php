@extends('layouts.app')

@section('title', 'New Application')

@section('content')

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-file-plus mr-2 text-blue-600"></i>New Application
                </h1>
                <a href="{{ route('student.applications.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Back
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('student.applications.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <!-- Academic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-graduation-cap mr-2 text-blue-600"></i>Academic Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Degree Program <span class="text-red-500">*</span></label>
                        <select name="degree_program"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('degree_program') border-red-400 @enderror">
                            <option value="">Select Program</option>
                            <option value="BS" {{ old('degree_program') === 'BS' ? 'selected' : '' }}>BS (4 Years)</option>
                            <option value="MS" {{ old('degree_program') === 'MS' ? 'selected' : '' }}>MS / MPhil</option>
                            <option value="PhD" {{ old('degree_program') === 'PhD' ? 'selected' : '' }}>PhD</option>
                            <option value="Associate Degree" {{ old('degree_program') === 'Associate Degree' ? 'selected' : '' }}>Associate Degree</option>
                        </select>
                        @error('degree_program')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                        <select name="department"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-400 @enderror">
                            <option value="">Select Department</option>
                            <option value="Computer Science" {{ old('department') === 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Software Engineering" {{ old('department') === 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                            <option value="Electrical Engineering" {{ old('department') === 'Electrical Engineering' ? 'selected' : '' }}>Electrical Engineering</option>
                            <option value="Business Administration" {{ old('department') === 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                            <option value="Mathematics" {{ old('department') === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                            <option value="Physics" {{ old('department') === 'Physics' ? 'selected' : '' }}>Physics</option>
                            <option value="English" {{ old('department') === 'English' ? 'selected' : '' }}>English</option>
                        </select>
                        @error('department')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                        <select name="semester"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semester') border-red-400 @enderror">
                            <option value="">Select Semester</option>
                            <option value="Fall" {{ old('semester') === 'Fall' ? 'selected' : '' }}>Fall</option>
                            <option value="Spring" {{ old('semester') === 'Spring' ? 'selected' : '' }}>Spring</option>
                            <option value="Summer" {{ old('semester') === 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        @error('semester')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admission Year <span class="text-red-500">*</span></label>
                        <select name="admission_year"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('admission_year') border-red-400 @enderror">
                            <option value="">Select Year</option>
                            @for($y = date('Y') + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ old('admission_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('admission_year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Previous Education -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-school mr-2 text-blue-600"></i>Previous Education
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Qualification <span class="text-red-500">*</span></label>
                        <select name="last_qualification"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_qualification') border-red-400 @enderror">
                            <option value="">Select Qualification</option>
                            <option value="Matric" {{ old('last_qualification') === 'Matric' ? 'selected' : '' }}>Matric (SSC)</option>
                            <option value="Intermediate" {{ old('last_qualification') === 'Intermediate' ? 'selected' : '' }}>Intermediate (HSSC)</option>
                            <option value="Bachelor" {{ old('last_qualification') === 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                            <option value="Master" {{ old('last_qualification') === 'Master' ? 'selected' : '' }}>Master</option>
                        </select>
                        @error('last_qualification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Institution Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_institution" value="{{ old('last_institution') }}"
                               placeholder="e.g. Government College Lahore"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_institution') border-red-400 @enderror">
                        @error('last_institution')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CGPA <span class="text-gray-400 text-xs">(if applicable)</span></label>
                        <input type="number" name="last_cgpa" value="{{ old('last_cgpa') }}"
                               placeholder="e.g. 3.5" step="0.01" min="0" max="4"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Percentage <span class="text-gray-400 text-xs">(if applicable)</span></label>
                        <input type="number" name="last_percentage" value="{{ old('last_percentage') }}"
                               placeholder="e.g. 85.5" step="0.01" min="0" max="100"
                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Passing Year <span class="text-red-500">*</span></label>
                        <select name="passing_year"
                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('passing_year') border-red-400 @enderror">
                            <option value="">Select Year</option>
                            @for($y = date('Y'); $y >= 2000; $y--)
                                <option value="{{ $y }}" {{ old('passing_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('passing_year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            <!-- Documents Upload -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    <i class="fas fa-folder-open mr-2 text-blue-600"></i>Documents Upload
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @php
                        $documents = [
                            'profile_photo'      => ['label' => 'Profile Photo', 'required' => true,  'accept' => 'image/*',           'hint' => 'JPG/PNG, max 2MB'],
                            'cnic_front'         => ['label' => 'CNIC Front',    'required' => true,  'accept' => 'image/*',           'hint' => 'JPG/PNG, max 2MB'],
                            'cnic_back'          => ['label' => 'CNIC Back',     'required' => true,  'accept' => 'image/*',           'hint' => 'JPG/PNG, max 2MB'],
                            'matric_certificate' => ['label' => 'Matric Certificate', 'required' => true, 'accept' => 'image/*,.pdf', 'hint' => 'JPG/PNG/PDF, max 5MB'],
                            'matric_marksheet'   => ['label' => 'Matric Marksheet',   'required' => true, 'accept' => 'image/*,.pdf', 'hint' => 'JPG/PNG/PDF, max 5MB'],
                            'inter_certificate'  => ['label' => 'Inter Certificate',  'required' => false,'accept' => 'image/*,.pdf', 'hint' => 'JPG/PNG/PDF, max 5MB'],
                            'inter_marksheet'    => ['label' => 'Inter Marksheet',    'required' => false,'accept' => 'image/*,.pdf', 'hint' => 'JPG/PNG/PDF, max 5MB'],
                            'domicile'           => ['label' => 'Domicile Certificate','required' => true,'accept' => 'image/*,.pdf', 'hint' => 'JPG/PNG/PDF, max 5MB'],
                        ];
                    @endphp

                    @foreach($documents as $field => $doc)
                        <div class="border rounded p-3 @error($field) border-red-400 bg-red-50 @else border-gray-200 @enderror">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $doc['label'] }}
                                @if($doc['required'])
                                    <span class="text-red-500">*</span>
                                @else
                                    <span class="text-gray-400 text-xs">(Optional)</span>
                                @endif
                            </label>
                            <input type="file" name="{{ $field }}"
                                   accept="{{ $doc['accept'] }}"
                                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3
                                          file:rounded file:border-0 file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">{{ $doc['hint'] }}</p>
                            @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Submit -->
            <div class="bg-white rounded-lg shadow p-6 flex justify-between items-center">
                <a href="{{ route('student.applications.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-times mr-1"></i>Cancel
                </a>
                <button type="submit"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-2 rounded font-semibold transition">
                    <i class="fas fa-save mr-2"></i>Save as Draft
                </button>
            </div>

        </form>
    </div>

@endsection