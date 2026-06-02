<div class="w-64 bg-[#0F2D52] text-white min-h-screen p-5">

    {{-- LOGO --}}
    <div class="mb-10 text-center">
        <img src="https://leads.edu.pk/wp-content/uploads/2021/02/cropped-cropped-LLU-LOGOf-1.png"
             class="w-20 mx-auto mb-2">
        <h2 class="text-sm text-gray-300">Student Portal</h2>
    </div>

    {{-- MENU --}}
    <ul class="space-y-3">

        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-[#C59B2B]">
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('profile.edit') }}"
               class="block px-3 py-2 rounded hover:bg-[#C59B2B]">
                My Profile
            </a>
        </li>

        <li>
            <a href="{{ route('student.applications.index') }}"
               class="block px-3 py-2 rounded hover:bg-[#C59B2B]">
                Application
            </a>
        </li>

    </ul>

</div>