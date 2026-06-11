@extends('layouts.app')
@section('title', 'Interview Schedules')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-white text-xs"></i>
            </span>
            Interview Schedules
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">Manage all student interviews</p>
    </div>
    <a href="{{ route('admin.interviews.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-plus mr-1"></i> Schedule Interview
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Roll No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Student</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Program</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Scheduled At</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Result</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($interviews as $interview)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold text-indigo-700">
                        {{ $interview->student_roll_no }}
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $interview->student->name }}</p>
                        <p class="text-xs text-gray-400">{{ $interview->student->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $interview->application->degree_program }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $interview->scheduled_at->format('d M Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $interview->scheduled_at->format('h:i A') }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'scheduled'   => 'bg-blue-100 text-blue-700',
                                'rescheduled' => 'bg-yellow-100 text-yellow-700',
                                'completed'   => 'bg-green-100 text-green-700',
                                'cancelled'   => 'bg-red-100 text-red-700',
                                'no_show'     => 'bg-gray-100 text-gray-600',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$interview->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($interview->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $resultColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'pass'    => 'bg-green-100 text-green-700',
                                'fail'    => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $resultColors[$interview->result] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($interview->result) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.interviews.show', $interview->id) }}"
                           class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-xs font-semibold">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        <i class="fas fa-calendar-times text-3xl mb-2 block"></i>
                        No interviews scheduled yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($interviews->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $interviews->links() }}
        </div>
    @endif
</div>

@endsection