<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex items-center space-x-6">

                <!-- LOGO -->
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="h-9 w-auto text-gray-800 dark:text-white" />
                </a>

                <!-- DASHBOARD LINK -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-nav-link>

            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center space-x-4">

                <!-- USER DROPDOWN -->
                <x-dropdown align="right" width="48">

                    <!-- TRIGGER (ONLY AVATAR CLICKABLE) -->
                    <x-slot name="trigger">
                        <button class="flex items-center focus:outline-none">

                            <img
                                src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.auth()->user()->name }}"
                                class="h-10 w-10 rounded-full object-cover border-2 border-gray-300 hover:border-blue-500 transition"
                                alt="User"
                            />

                        </button>
                    </x-slot>

                    <!-- DROPDOWN CONTENT -->
                    <x-slot name="content">

                        <!-- USER INFO HEADER -->
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm font-semibold text-gray-700">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <!-- LINKS -->
                        <x-dropdown-link :href="route('profile.edit')">
                             My Profile
                        </x-dropdown-link>

                        <div class="border-t my-1"></div>

                        <!-- LOGOUT -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                 Logout
                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

                <!-- MOBILE MENU BUTTON -->
                <button @click="open = ! open" class="sm:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t">

        <div class="px-4 py-2 space-y-1">

            <x-responsive-nav-link :href="route('profile.edit')">
                Profile
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('applications.index')">
                My Application
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>

            </form>

        </div>

    </div>
<div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">

        <h2 class="text-xl font-bold mb-4">Edit Profile</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <input type="text" name="name" value="{{ auth()->user()->name }}"
                   class="w-full border p-2 mb-3 rounded">

            <input type="email" name="email" value="{{ auth()->user()->email }}"
                   class="w-full border p-2 mb-3 rounded">

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeProfileModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                    Save
                </button>
            </div>
        </form>

    </div>
</div>
<script>
function openProfileModal() {
    document.getElementById('profileModal').classList.remove('hidden');
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden');
}
</script>
</nav>