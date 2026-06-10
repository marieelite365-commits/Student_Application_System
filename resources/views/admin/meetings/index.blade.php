@extends('layouts.app')

@section('title', 'Meetings')

@section('content')

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-video mr-2 text-blue-600"></i>Meetings
            </h1>
            <a href="{{ route('admin.meetings.create') }}"
               class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded font-semibold text-sm">
                <i class="fas fa-plus mr-1"></i>Schedule Meeting
            </a>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Meetings Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Date & Time</th>
                    <th class="px-4 py-3 text-left">For</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($meetings as $meeting)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $meeting->title }}</p>
                            <p class="text-xs text-gray-400">By: {{ $meeting->creator->name }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ ucfirst($meeting->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p>{{ $meeting->start_time->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $meeting->start_time->format('h:i A') }} — {{ $meeting->end_time->format('h:i A') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($meeting->for === 'all')
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">All Students</span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Specific</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($meeting->status === 'scheduled') bg-green-100 text-green-700
                                @elseif($meeting->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($meeting->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.meetings.show', $meeting->id) }}"
                               class="text-blue-600 hover:underline text-xs">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.meetings.edit', $meeting->id) }}"
                               class="text-yellow-600 hover:underline text-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.meetings.destroy', $meeting->id) }}"
                                  onsubmit="return confirm('Delete this meeting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            No meetings scheduled yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection