<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected string $accountId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->accountId    = env('ZOOM_ACCOUNT_ID');
        $this->clientId     = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
    }

    // ─── Access Token ─────────────────────────────────────────
    private function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 3500, function () {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->failed()) {
                Log::error('Zoom token failed: ' . $response->body());
                throw new \Exception('Zoom authentication failed');
            }

            return $response->json()['access_token'];
        });
    }

    // ─── Meeting Create ───────────────────────────────────────
    public function createMeeting(
        string $topic,
        string $startTime,
        int    $duration = 30,
        string $agenda   = ''
    ): array {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/users/me/meetings", [
                'topic'      => $topic,
                'type'       => 2,
                'start_time' => $startTime,
                'duration'   => $duration,
                'agenda'     => $agenda,
                'settings'   => [
                    'host_video'        => true,
                    'participant_video'  => true,
                    'waiting_room'      => true,
                    'auto_recording'    => 'cloud',
                ],
            ]);

        if ($response->failed()) {
            Log::error('Zoom meeting create failed: ' . $response->body());
            throw new \Exception('Failed to create Zoom meeting: ' . $response->body());
        }

        $data = $response->json();

        return [
            'meeting_id'   => $data['id'],
            'join_url'     => $data['join_url'],
            'start_url'    => $data['start_url'],
            'password'     => $data['password'],
            'start_time'   => $data['start_time'],
            'duration'     => $data['duration'],
        ];
    }

    // ─── Meeting Delete ───────────────────────────────────────
    public function deleteMeeting(string $meetingId): void
    {
        $token = $this->getAccessToken();

        Http::withToken($token)
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");
    }

    // ─── Meeting Get ──────────────────────────────────────────
    public function getMeeting(string $meetingId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/meetings/{$meetingId}");

        if ($response->failed()) {
            throw new \Exception('Meeting not found');
        }

        return $response->json();
    }
}