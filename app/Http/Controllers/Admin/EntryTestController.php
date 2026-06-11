<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntryTest;
use App\Models\EntryTestQuestion;
use App\Models\InterviewSchedule;
use Illuminate\Http\Request;

class EntryTestController extends Controller
{
    // ─── List All Tests ───────────────────────────────────────
    public function index()
    {
        $tests = EntryTest::with(['student', 'application'])
            ->latest()
            ->paginate(15);

        return view('admin.entry_tests.index', compact('tests'));
    }

    // ─── Questions List ───────────────────────────────────────
    public function questions()
    {
        $questions = EntryTestQuestion::latest()->paginate(15);
        return view('admin.entry_tests.questions', compact('questions'));
    }

    // ─── Add Question Form ────────────────────────────────────
    public function createQuestion()
    {
        return view('admin.entry_tests.create_question');
    }

    // ─── Store Question ───────────────────────────────────────
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'category'       => 'required|string',
        ]);

        EntryTestQuestion::create([
            'question'       => $request->question,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'marks'          => 1,
            'category'       => $request->category,
            'is_active'      => true,
        ]);

        return redirect()->route('admin.entry_tests.questions')
            ->with('success', 'Question added successfully!');
    }

    // ─── Delete Question ──────────────────────────────────────
    public function deleteQuestion(EntryTestQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted successfully!');
    }

    // ─── Show Test Result ─────────────────────────────────────
    public function show(EntryTest $entryTest)
    {
        $entryTest->load(['student', 'application', 'answers.question']);
        return view('admin.entry_tests.show', compact('entryTest'));
    }
}