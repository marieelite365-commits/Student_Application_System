@extends('layouts.app')
@section('title', 'Entry Tests')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-white text-xs"></i>
            </span>
            Entry Tests
        </h1>
        <p class="text-gray-400 text-xs mt-0.5 ml-9">All student entry test results</p>
    </div>
    <a href="{{ route('admin.entry_tests.questions') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
        <i class="fas fa-question-circle mr-1"></i> Manage Questions
    </a>
</div>

{{-- Success --}}
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
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Student</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Program</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Marks</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Result</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tests as $test)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $test->student->name }}</p>
                        <p class="text-xs text-gray-400">{{ $test->student->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $test->application->degree_program }}
                    </td>
                    <td class="px-4 py-3">
                        @if($test->status === 'completed')
                            <span class="font-semibold text-gray-800">
                                {{ $test->obtained_marks }}/{{ $test->total_marks }}
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'pending'     => 'bg-yellow-100 text-yellow-700',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'completed'   => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$test->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst(str_replace('_', ' ', $test->status)) }}
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
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $resultColors[$test->result] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($test->result) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $test->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.entry_tests.show', $test->id) }}"
                           class="bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        <i class="fas fa-file-alt text-3xl mb-2 block"></i>
                        No entry tests yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($tests->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $tests->links() }}
        </div>
    @endif
</div>

@endsection