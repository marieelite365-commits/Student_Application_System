<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GoogleDriveToken;
use Carbon\Carbon;
use Google\Service\Calendar;
use Google\Service\Classroom;
use App\Models\GoogleClassroomToken;

class GoogleController extends Controller
{
    public function redirect()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return redirect()->away($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Google connection failed: ' . $token['error']);
        }

        $user = Auth::user();

        GoogleDriveToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'access_token'  => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'token_type'    => $token['token_type'] ?? 'Bearer',
                'expires_at'    => Carbon::now()->addSeconds($token['expires_in'] ?? 3600),
            ]
        );

        return redirect()->route('student.dashboard')
            ->with('success', 'Google Drive connected successfully!');
    }
    // ─── Calendar OAuth ───────────────────────────────────────────
public function calendarRedirect()
{
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
    $client->addScope(Calendar::CALENDAR);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    return redirect()->away($client->createAuthUrl());
}

public function calendarCallback(Request $request)
{
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));

    $token = $client->fetchAccessTokenWithAuthCode($request->code);

    if (isset($token['error'])) {
        return redirect()->route('admin.meetings.index')
            ->with('error', 'Google Calendar connection failed: ' . $token['error']);
    }

    // Admin ka token save karo — user_id 0 matlab system/admin token
    GoogleDriveToken::updateOrCreate(
        ['user_id' => Auth::id()],
        [
            'access_token'  => $token['access_token'],
            'refresh_token' => $token['refresh_token'] ?? null,
            'token_type'    => $token['token_type'] ?? 'Bearer',
            'expires_at'    => Carbon::now()->addSeconds($token['expires_in'] ?? 3600),
        ]
    );

    return redirect()->route('admin.meetings.index')
        ->with('success', 'Google Calendar connected successfully! Meetings will now be automatically added to the calendar.');
   }

   // ─── Classroom OAuth ──────────────────────────────────────────
public function classroomCallback(Request $request)
{
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));

    $token = $client->fetchAccessTokenWithAuthCode($request->code);

    if (isset($token['error'])) {
        // ✅ Role check karke redirect karo
        $route = Auth::user()->hasRole('admin') 
            ? 'admin.meetings.index' 
            : 'student.dashboard';
            
        return redirect()->route($route)
            ->with('error', 'Google Classroom connection failed: ' . $token['error']);
    }

    GoogleClassroomToken::updateOrCreate(
        ['user_id' => Auth::id()],
        [
            'access_token'  => $token['access_token'],
            'refresh_token' => $token['refresh_token'] ?? null,
            'token_type'    => $token['token_type'] ?? 'Bearer',
            'expires_at'    => Carbon::now()->addSeconds($token['expires_in'] ?? 3600),
        ]
    );

    // ✅ Role check karke sahi dashboard pe bhejo
    $route = Auth::user()->hasRole('admin') 
        ? 'admin.dashboard' 
        : 'student.dashboard';

    return redirect()->route($route)
        ->with('success', 'Google Classroom connected successfully!');
}
public function classroomRedirect()
{
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
    $client->addScope(Classroom::CLASSROOM_COURSES);
    $client->addScope(Classroom::CLASSROOM_ROSTERS);
    $client->addScope(Classroom::CLASSROOM_ANNOUNCEMENTS);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent'); // ✅ Fix
    
    return redirect()->away($client->createAuthUrl());
}


}