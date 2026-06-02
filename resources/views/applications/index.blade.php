<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold">My Applications</h2>
    </x-slot>

    <div class="p-6">

        @foreach($applications as $application)

            <div class="bg-white p-6 rounded-lg shadow mb-4">

                <p><b>Username:</b> {{ $application->username }}</p>
                <p><b>CNIC:</b> {{ $application->cnic }}</p>
                <p><b>Degree:</b> {{ $application->degree }}</p>
                <p><b>Status:</b>
                     @if($application->status == 'Pending')
                      <span class="px-3 py-1 text-sm bg-yellow-200 text-yellow-800 rounded-full">
                           Pending
                      </span>

                          @elseif($application->status == 'Approved')
                      <span class="px-3 py-1 text-sm bg-green-200 text-green-800 rounded-full">
                           Approved
                      </span>

                         @elseif($application->status == 'Rejected')
                      <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">
                           Rejected
                      </span>

                          @else
                      <span class="px-3 py-1 text-sm bg-gray-200 text-gray-800 rounded-full">
                          {{ $application->status }}
                      </span>
                        @endif
              </p>

                <div class="mt-2">
                    <b>Image:</b><br>
                    <img src="{{ asset('storage/'.$application->image) }}"
                         class="w-28 h-28 rounded object-cover mt-2">
                </div>

                <div class="mt-2">
                    <b>Document:</b><br>
                    <a href="{{ asset('storage/'.$application->document) }}"
                       target="_blank"
                       class="text-blue-600 underline">
                        View File
                    </a>
                </div>

            </div>

        @endforeach

    </div>

</x-app-layout>