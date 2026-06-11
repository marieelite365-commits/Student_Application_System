@extends('layouts.app')
@section('title', 'Schedule Interview')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-white text-xs"></i>
            </span>
            Schedule Interview
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">Create Zoom interview for student</p>
    </div>
    <a href="{{ route('admin.interviews.index') }}"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

{{-- Error --}}
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Form --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.interviews.store') }}" method="POST">
                @csrf

                {{-- Application Select --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Select Student Application <span class="text-red-500">*</span>
                    </label>
                    <select name="application_id" id="application_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            onchange="loadStudentInfo(this)">
                        <option value="">-- Select Application --</option>
                        @foreach($applications as $application)
                            <option value="{{ $application->id }}"
                                {{ (isset($selectedApplication) && $selectedApplication->id == $application->id) ? 'selected' : '' }}
                                data-name="{{ $application->user->name }}"
                                data-email="{{ $application->user->email }}"
                                data-program="{{ $application->degree_program }}"
                                data-department="{{ $application->department }}">
                                {{ $application->user->name }} — {{ $application->degree_program }}
                            </option>
                        @endforeach
                    </select>
                    @error('application_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date & Time --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Interview Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at') }}"
                           min="{{ \Carbon\Carbon::now('Asia/Karachi')->addMinutes(30)->format('Y-m-d\TH:i') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @error('scheduled_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Duration <span class="text-red-500">*</span>
                    </label>
                    <select name="duration"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">60 minutes</option>
                    </select>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-semibold text-sm">
                    <i class="fas fa-video mr-2"></i> Create Zoom Interview
                </button>
            </form>
        </div>
    </div>

    {{-- Student Info Preview --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5" id="student-info-box">
            <p class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-user mr-1 text-indigo-500"></i> Student Info
            </p>
            <div id="student-details" class="text-sm text-gray-400 text-center py-6">
                <i class="fas fa-user-circle text-3xl mb-2 block"></i>
                Select an application to preview
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mt-4">
            <p class="text-xs font-semibold text-indigo-700 mb-2">
                <i class="fas fa-info-circle mr-1"></i> What happens next?
            </p>
            <ul class="text-xs text-indigo-600 space-y-1.5">
                <li><i class="fas fa-check mr-1"></i> Zoom meeting will be created</li>
                <li><i class="fas fa-check mr-1"></i> Student Roll No auto-generated</li>
                <li><i class="fas fa-check mr-1"></i> Details saved to Google Drive</li>
                <li><i class="fas fa-check mr-1"></i> Application status updated</li>
            </ul>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function loadStudentInfo(select) {
    const option = select.options[select.selectedIndex];
    const box = document.getElementById('student-details');

    if (!option.value) {
        box.innerHTML = '<i class="fas fa-user-circle text-3xl mb-2 block text-gray-300"></i><p>Select an application to preview</p>';
        return;
    }

    box.innerHTML = `
        <div class="space-y-2 text-left">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-indigo-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">${option.dataset.name}</p>
                    <p class="text-xs text-gray-400">${option.dataset.email}</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 mt-2 space-y-1">
                <p class="text-xs"><span class="text-gray-500">Program:</span> <span class="font-medium text-gray-700">${option.dataset.program}</span></p>
                <p class="text-xs"><span class="text-gray-500">Department:</span> <span class="font-medium text-gray-700">${option.dataset.department}</span></p>
            </div>
        </div>
    `;
}

// Agar URL mein application_id hai toh auto load karo
window.onload = function() {
    const select = document.getElementById('application_id');
    if (select.value) loadStudentInfo(select);
}
</script>
@endpush