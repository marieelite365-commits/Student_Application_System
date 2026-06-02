{{-- PROFILE PAGE CLEAN SAFE VERSION --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- PROFILE --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- APPLICATION --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-6 rounded-xl">

                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                    My Application
                </h3>

                @if($application)

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Application Status</p>

                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold
                            @if($application->status == 'Pending') bg-yellow-200 text-yellow-800
                            @elseif($application->status == 'Approved') bg-green-200 text-green-800
                            @elseif($application->status == 'Rejected') bg-red-200 text-red-800
                            @else bg-gray-200 text-gray-700 @endif">

                            {{ $application->status ?? 'Pending' }}
                        </span>
                    </div>

                    {{-- INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <b>Name:</b> {{ $application->username ?? 'Not Set' }}
                        </div>

                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <b>Email:</b> {{ auth()->user()->email }}
                        </div>

                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <b>CNIC:</b> {{ $application->cnic ?? 'Not Set' }}
                        </div>

                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <b>Degree:</b> {{ $application->degree ?? 'Not Set' }}
                        </div>

                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <b>Program:</b> {{ $application->program ?? 'Not Set' }}
                        </div>

                    </div>

                    {{-- DOCUMENT --}}
                    <div class="mt-4">
                        <a href="{{ $application->document ? asset('storage/'.$application->document) : '#' }}"
                           target="_blank"
                           class="text-blue-600 underline">
                            View Document
                        </a>
                    </div>

                    {{-- BUTTON --}}
                    <button onclick="openAppModal()"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
                        Edit Application
                    </button>

                @else
                    <p class="text-gray-500">No application found.</p>
                @endif

            </div>

            {{-- 2FA --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Two Factor Authentication
                    </h3>

                    @if (!auth()->user()->two_factor_secret)

                        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Enable 2FA
                            </button>
                        </form>

                    @else

                        <p class="text-green-500">2FA enabled</p>

                        <div class="mt-4">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                    @endif

                </div>
            </div>

            {{-- DELETE --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL SAFE --}}
    @if($application)
    <div id="appModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">

        <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg">

            <h2 class="text-xl font-bold mb-4">Edit Application</h2>

            <form method="POST" action="{{ route('student.applications.update', $application->id) }}">
                @csrf

                <label>Username</label>
                <input name="username" value="{{ $application->username ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>CNIC</label>
                <input name="cnic" value="{{ $application->cnic ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>Degree</label>
                <input name="degree" value="{{ $application->degree ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>Age</label>
                <input name="age" value="{{ $application->age ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>Phone</label>
                <input name="phone" value="{{ $application->phone ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>Program</label>
                <input name="program" value="{{ $application->program ?? '' }}" class="w-full border p-2 mb-2 rounded">

                <label>Image</label>
                <input type="file" name="image" class="w-full border p-2 mb-2 rounded">

                <label>Document</label>
                <input type="file" name="document" class="w-full border p-2 mb-2 rounded">

                <div class="flex justify-end gap-3 mt-5">

                    <button type="button"
                        onclick="closeAppModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded">
                        Close
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>
    </div>
    @endif

    <script>
        function openAppModal() {
            document.getElementById('appModal')?.classList.remove('hidden');
        }

        function closeAppModal() {
            document.getElementById('appModal')?.classList.add('hidden');
        }
    </script>

</x-app-layout>