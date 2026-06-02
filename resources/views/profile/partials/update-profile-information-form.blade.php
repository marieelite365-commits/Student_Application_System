<section class="bg-white p-6 rounded-lg shadow">

<div class="p-6 bg-white shadow rounded-lg">

    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">

        <!-- LEFT -->
        <div class="space-y-2">
            <h2 class="text-xl font-bold">{{ auth()->user()->name }}</h2>
            <p class="text-gray-600">{{ auth()->user()->email }}</p>
        </div>

        <!-- RIGHT (IMAGE ONLY HERE) -->
       <div class="flex justify-center">
    <img
    src="{{ isset($application) && $application->image 
        ? asset('storage/'.$application->image)
        : 'https://ui-avatars.com/api/?name='.auth()->user()->name }}"
    class="w-28 h-28 rounded-full object-cover border-4 border-gray-300"
      />
</div>
    </div>

</div>

</section>