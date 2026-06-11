<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\GoogleDriveToken;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleCalendarService
{
    protected ?Client $client = null;
    protected ?Calendar $calendarService = null;
    protected string $calendarId;

    public function __construct()
    {
        $this->calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
    }

    private function getClient(): ?Client
    {
        if (null !== $this->client) {
            return $this->client;
        }

        
        // Kisi bhi admin ka token lo
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        $tokenRecord = GoogleDriveToken::whereIn('user_id', $adminIds)->latest()->first();

        if (!$tokenRecord) {
            Log::warning('Google Calendar: No admin token found.');
            return null;
        }

        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
        $this->client->setAccessType('offline');

        $tokenArray = [
            'access_token'  => $tokenRecord->access_token,
            'refresh_token' => $tokenRecord->refresh_token,
            'token_type'    => $tokenRecord->token_type,
            'expires_at'    => $tokenRecord->expires_at?->timestamp,
        ];

        $this->client->setAccessToken($tokenArray);

        // Token expire ho gaya hai toh refresh karo
        if ($this->client->isAccessTokenExpired()) {
            if ($tokenRecord->refresh_token) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken(
                    $tokenRecord->refresh_token
                );
                if (!isset($newToken['error'])) {
                    $tokenRecord->update([
                        'access_token' => $newToken['access_token'],
                        'expires_at'   => Carbon::now()->addSeconds($newToken['expires_in'] ?? 3600),
                    ]);
                    $this->client->setAccessToken($newToken);
                } else {
                    Log::error('Calendar token refresh failed: ' . $newToken['error']);
                    return null;
                }
            } else {
                Log::warning('Calendar token expired and no refresh token.');
                return null;
            }
        }

        return $this->client;
    }

    private function getCalendarService(): ?Calendar
    {
        if (null !== $this->calendarService) {
            return $this->calendarService;
        }

        $client = $this->getClient();
        if (!$client) return null;

        $this->calendarService = new Calendar($client);
        return $this->calendarService;
    }

    public function createEvent(
        string $title,
        string $description,
        string $startTime,
        string $endTime
    ): ?array {
        $calendarService = $this->getCalendarService();
        if (!$calendarService) return null;

        try {
            $event = new Event([
                'summary'     => $title,
                'description' => $description,
                'start'       => new EventDateTime([
                    'dateTime' => $startTime,
                    'timeZone' => config('app.timezone', 'Asia/Karachi'),
                ]),
                'end'         => new EventDateTime([
                    'dateTime' => $endTime,
                    'timeZone' => config('app.timezone', 'Asia/Karachi'),
                ]),
                'conferenceData' => [
                    'createRequest' => [
                        'requestId'             => uniqid(),
                        'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    ],
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides'  => [
                        ['method' => 'email', 'minutes' => 1440],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ]);

            $createdEvent = $calendarService->events->insert(
                $this->calendarId,
                $event,
                ['conferenceDataVersion' => 1]
            );

            $meetLink = null;
            $conferenceData = $createdEvent->getConferenceData();
            if ($conferenceData) {
                foreach ($conferenceData->getEntryPoints() as $entryPoint) {
                    if ($entryPoint->getEntryPointType() === 'video') {
                        $meetLink = $entryPoint->getUri();
                        break;
                    }
                }
            }

            return [
                'event_id'  => $createdEvent->getId(),
                'meet_link' => $meetLink,
            ];

        } catch (\Exception $e) {
            Log::error('Google Calendar createEvent failed: ' . $e->getMessage());
            return null;
        }
    }

    public function updateEvent(
        string $eventId,
        string $title,
        string $description,
        string $startTime,
        string $endTime
    ): bool {
        $calendarService = $this->getCalendarService();
        if (!$calendarService) return false;

        try {
            $event = $calendarService->events->get($this->calendarId, $eventId);
            $event->setSummary($title);
            $event->setDescription($description);
            $event->setStart(new EventDateTime([
                'dateTime' => $startTime,
                'timeZone' => config('app.timezone', 'Asia/Karachi'),
            ]));
            $event->setEnd(new EventDateTime([
                'dateTime' => $endTime,
                'timeZone' => config('app.timezone', 'Asia/Karachi'),
            ]));
            $calendarService->events->update($this->calendarId, $eventId, $event);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar updateEvent failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent(string $eventId): bool
    {
        $calendarService = $this->getCalendarService();
        if (!$calendarService) return false;

        try {
            $calendarService->events->delete($this->calendarId, $eventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar deleteEvent failed: ' . $e->getMessage());
            return false;
        }
    }
    // ─── Interview Event Create (Admission System) ────────────
    public function createInterviewEvent(
      string $studentName,
      string $studentEmail,
      string $rollNo,
      string $degreeProgram,
      string $department,
      string $scheduledAt,
      int    $duration,
      string $zoomJoinUrl,
      string $zoomMeetingId,
      string $zoomPassword
      ): array {
    try {
        $startTime = \Carbon\Carbon::parse($scheduledAt, 'Asia/Karachi');
        $endTime   = $startTime->copy()->addMinutes($duration);

        $description  = "ADMISSION INTERVIEW\n";
        $description .= "===================\n\n";
        $description .= "Student Name   : {$studentName}\n";
        $description .= "Roll No        : {$rollNo}\n";
        $description .= "Degree Program : {$degreeProgram}\n";
        $description .= "Department     : {$department}\n\n";
        $description .= "ZOOM DETAILS\n";
        $description .= "------------\n";
        $description .= "Meeting ID : {$zoomMeetingId}\n";
        $description .= "Password   : {$zoomPassword}\n";
        $description .= "Join URL   : {$zoomJoinUrl}\n";

        // ─── Attendee (Student) Add ────────────────────────
        $calendarService = $this->getCalendarService();
        if (!$calendarService) {
            return ['success' => false, 'error' => 'Calendar service unavailable — admin token missing.'];
        }

        $event = new \Google\Service\Calendar\Event([
            'summary'     => 'Admission Interview — ' . $studentName . ' (' . $rollNo . ')',
            'description' => $description,
            'location'    => $zoomJoinUrl,
            'attendees'   => [
                ['email' => $studentEmail],
            ],
            'start' => new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $startTime->toRfc3339String(),
                'timeZone' => 'Asia/Karachi',
            ]),
            'end' => new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $endTime->toRfc3339String(),
                'timeZone' => 'Asia/Karachi',
            ]),
            'reminders' => [
                'useDefault' => false,
                'overrides'  => [
                    ['method' => 'email', 'minutes' => 1440],
                    ['method' => 'popup', 'minutes' => 30],
                ],
            ],
            'status' => 'confirmed',
        ]);

        $createdEvent = $calendarService->events->insert(
            $this->calendarId,
            $event,
            ['sendUpdates' => 'all']
        );

        Log::info('Calendar interview event created: ' . $createdEvent->getId());

        return [
            'success'   => true,
            'event_id'  => $createdEvent->getId(),
            'event_url' => $createdEvent->getHtmlLink(),
        ];

    } catch (\Exception $e) {
        Log::error('Calendar createInterviewEvent failed: ' . $e->getMessage());
        return [
            'success' => false,
            'error'   => $e->getMessage(),
        ];
    }
    }
}
