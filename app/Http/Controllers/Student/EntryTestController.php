<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EntryTest;
use App\Models\EntryTestQuestion;
use App\Models\EntryTestAnswer;
use App\Models\InterviewSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryTestController extends Controller
{
    // ─── Start Test ───────────────────────────────────────────
    public function start(EntryTest $entryTest)
    {
        if ($entryTest->student_id !== Auth::id()) {
            abort(403);
        }

        if ($entryTest->status === 'completed') {
            return redirect()->route('student.entry_tests.result', $entryTest->id);
        }

        // Questions load karo — degree program se
        $program  = $entryTest->application->degree_program;
        $questions = EntryTestQuestion::where('category', $program)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        // Agar us program ke questions kam hain toh general se bhi lo
        if ($questions->count() < 10) {
            $remaining = 10 - $questions->count();
            $general   = EntryTestQuestion::where('category', 'general')
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit($remaining)
                ->get();
            $questions = $questions->merge($general);
        }

        // Test start karo
        if ($entryTest->status === 'pending') {
            $entryTest->update([
                'status'     => 'in_progress',
                'started_at' => now(),
            ]);
        }

        return view('student.entry_tests.start', compact('entryTest', 'questions'));
    }

    // ─── Submit Test ──────────────────────────────────────────
    public function submit(Request $request, EntryTest $entryTest)
    {
        if ($entryTest->student_id !== Auth::id()) {
            abort(403);
        }

        if ($entryTest->status === 'completed') {
            return redirect()->route('student.entry_tests.result', $entryTest->id);
        }

        $answers      = $request->input('answers', []);
        $obtainedMarks = 0;

        foreach ($answers as $questionId => $selectedAnswer) {
            $question  = EntryTestQuestion::find($questionId);
            $isCorrect = $question && strtolower($selectedAnswer) === strtolower($question->correct_answer);

            if ($isCorrect) {
                $obtainedMarks++;
            }

            EntryTestAnswer::create([
                'entry_test_id'   => $entryTest->id,
                'question_id'     => $questionId,
                'student_id'      => Auth::id(),
                'selected_answer' => $selectedAnswer,
                'is_correct'      => $isCorrect,
            ]);
        }

        // Result calculate karo
        $result = $obtainedMarks >= $entryTest->passing_marks ? 'pass' : 'fail';

        $entryTest->update([
            'status'         => 'completed',
            'result'         => $result,
            'obtained_marks' => $obtainedMarks,
            'completed_at'   => now(),
        ]);

        // Application status update
        if ($result === 'pass') {
            $entryTest->application->update(['status' => 'approved']);
        } else {
            $entryTest->application->update(['status' => 'rejected']);
        }

        return redirect()->route('student.entry_tests.result', $entryTest->id);
    }

    // ─── Result ───────────────────────────────────────────────
    public function result(EntryTest $entryTest)
    {
        if ($entryTest->student_id !== Auth::id()) {
            abort(403);
        }

        $entryTest->load(['answers.question', 'application']);

        return view('student.entry_tests.result', compact('entryTest'));
    }
}