<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GoogleDriveToken;
use Carbon\Carbon;

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

        // Token fetch karo
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Google connection failed: ' . $token['error']);
        }

        $user = Auth::user();

        // google_drive_tokens table mein save karo
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
}