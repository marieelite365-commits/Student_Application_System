<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Student\MeetingController as StudentMeetingController;
use App\Http\Controllers\Admin\MeetingController as AdminMeetingController;
use App\Services\GoogleDriveService;

// ─── Public Routes ────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// ─── Breeze Auth Routes ───────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Google OAuth Routes ──────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// ─── Application Routes (for both students and admins) ─────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/application/create', [ApplicationController::class, 'create'])
         ->name('applications.create');
    Route::post('/application/store', [ApplicationController::class, 'store'])
         ->name('applications.store');
});

// ─── Google Calendar OAuth Routes ────────────────────────────
Route::get('/google/calendar/redirect', [GoogleController::class, 'calendarRedirect'])->name('google.calendar.redirect');
Route::get('/google/calendar/callback', [GoogleController::class, 'calendarCallback'])->name('google.calendar.callback');

// ─── Google Classroom OAuth Routes ───────────────────────────
Route::get('/google/classroom/redirect', [GoogleController::class, 'classroomRedirect'])->name('google.classroom.redirect');
Route::get('/google/classroom/callback', [GoogleController::class, 'classroomCallback'])->name('google.classroom.callback');

// ─── Student Routes ───────────────────────────────────────────
Route::middleware(['auth', 'verified', 'student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/create', [ProfileController::class, 'create'])->name('create');
        Route::post('/create', [ProfileController::class, 'store'])->name('store');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/edit', [ProfileController::class, 'update'])->name('update');
    });

    // Applications
    Route::middleware(['profile.complete'])
    ->prefix('applications')
    ->name('applications.')
    ->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create', [ApplicationController::class, 'create'])->name('create');
        Route::post('/create', [ApplicationController::class, 'store'])->name('store');
        Route::get('/{application}', [ApplicationController::class, 'show'])->name('show');
        Route::post('/{application}/submit', [ApplicationController::class, 'submit'])->name('submit');
        Route::post('/{application}/update', [ApplicationController::class, 'update'])->name('update');
    });
    // Meetings
    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [StudentMeetingController::class, 'index'])->name('index');
        Route::get('/{meeting}', [StudentMeetingController::class, 'show'])->name('show');
        Route::get('/{meeting}/join', [StudentMeetingController::class, 'join'])->name('join');
    });

    // ─── Interview Routes ─────────────────────────────────────────
    Route::prefix('interviews')->name('interviews.')->group(function () {
      Route::get('/', [App\Http\Controllers\Student\InterviewController::class, 'index'])->name('index');
      Route::get('/{interview}', [App\Http\Controllers\Student\InterviewController::class, 'show'])->name('show');
    });

    // ─── Entry Test Routes ────────────────────────────────────────
    Route::prefix('entry-tests')->name('entry_tests.')->group(function () {
      Route::get('/{entryTest}/start', [App\Http\Controllers\Student\EntryTestController::class, 'start'])->name('start');
      Route::post('/{entryTest}/submit', [App\Http\Controllers\Student\EntryTestController::class, 'submit'])->name('submit');
      Route::get('/{entryTest}/result', [App\Http\Controllers\Student\EntryTestController::class, 'result'])->name('result');
    });

    // Notifications
    Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllRead'])
    ->name('notifications.markAllRead');

});

// ─── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
        Route::post('/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('status');
    });

    // Students
        Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'students'])->name('index');
        Route::get('/{user}', [AdminApplicationController::class, 'showStudent'])->name('show');
    });
    // Meetings
    Route::resource('meetings', AdminMeetingController::class);

    // ─── Interview Routes ─────────────────────────────────────────
     Route::prefix('interviews')->name('interviews.')->group(function () {
     Route::get('/', [App\Http\Controllers\Admin\InterviewController::class, 'index'])->name('index');
     Route::get('/create', [App\Http\Controllers\Admin\InterviewController::class, 'create'])->name('create');
     Route::post('/store', [App\Http\Controllers\Admin\InterviewController::class, 'store'])->name('store');
     Route::get('/{interview}', [App\Http\Controllers\Admin\InterviewController::class, 'show'])->name('show');
     Route::post('/{interview}/result', [App\Http\Controllers\Admin\InterviewController::class, 'markResult'])->name('result');
     Route::post('/{interview}/cancel', [App\Http\Controllers\Admin\InterviewController::class, 'cancel'])->name('cancel');
    });

    // ─── Entry Test Routes ────────────────────────────────────────
    Route::prefix('entry-tests')->name('entry_tests.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\EntryTestController::class, 'index'])->name('index');
    Route::get('/questions', [App\Http\Controllers\Admin\EntryTestController::class, 'questions'])->name('questions');
    Route::get('/questions/create', [App\Http\Controllers\Admin\EntryTestController::class, 'createQuestion'])->name('questions.create');
    Route::post('/questions/store', [App\Http\Controllers\Admin\EntryTestController::class, 'storeQuestion'])->name('questions.store');
    Route::delete('/questions/{question}', [App\Http\Controllers\Admin\EntryTestController::class, 'deleteQuestion'])->name('questions.delete');
    Route::get('/{entryTest}', [App\Http\Controllers\Admin\EntryTestController::class, 'show'])->name('show');
    });
    
    // ─── Google Classroom Routes ──────────────────────────────────
    Route::prefix('admin/classroom')->name('admin.classroom.')->group(function () {
    Route::get('/',                      [App\Http\Controllers\Admin\ClassroomController::class, 'index'])->name('index');
    Route::get('/create',                [App\Http\Controllers\Admin\ClassroomController::class, 'create'])->name('create');
    Route::post('/',                     [App\Http\Controllers\Admin\ClassroomController::class, 'store'])->name('store');
    Route::get('/{id}',                  [App\Http\Controllers\Admin\ClassroomController::class, 'show'])->name('show');
    Route::post('/{id}/announce',        [App\Http\Controllers\Admin\ClassroomController::class, 'announce'])->name('announce');
    Route::post('/{id}/enroll',          [App\Http\Controllers\Admin\ClassroomController::class, 'enroll'])->name('enroll');
    Route::post('/{id}/archive',         [App\Http\Controllers\Admin\ClassroomController::class, 'archive'])->name('archive');
});
    
Route::get('/test-drive', function () {

    $service = app(GoogleDriveService::class);

    $folderId = $service->getStudentFolder(
        1,
        'Test Student'
    );

    dd($folderId);
});
Route::get('/test-zoom', function () {
    $zoom = app(\App\Services\ZoomService::class);
    try {
        $meeting = $zoom->createMeeting(
            topic: 'Test Meeting',
            startTime: \Carbon\Carbon::now('Asia/Karachi')->addHours(2)->format('Y-m-d\TH:i:s'),
            duration: 30,
            agenda: 'Test'
        );
        dd($meeting);
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});
});