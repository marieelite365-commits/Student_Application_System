<?php

namespace App\Services;

use Google\Client;
use Google\Service\Classroom;
use Google\Service\Classroom\Course;
use Google\Service\Classroom\Announcement;
use Google\Service\Classroom\Student;
use Illuminate\Support\Facades\Log;

class GoogleClassroomService
{
    protected ?Client $client = null;
    protected ?Classroom $classroomService = null;

    public function __construct()
    {
        // Empty constructor - lazy loading
    }

    // ─── Get Client ──────────────────────────────────────────
    private function getClient(): Client
    {
        if (null === $this->client) {
            $this->client = new Client();
            $this->client->setAuthConfig(
                base_path(env('GOOGLE_APPLICATION_CREDENTIALS'))
            );
            $this->client->addScope(Classroom::CLASSROOM_COURSES);
            $this->client->addScope(Classroom::CLASSROOM_ANNOUNCEMENTS);
            $this->client->addScope(Classroom::CLASSROOM_ROSTERS);
            $this->client->setSubject(env('GOOGLE_ADMIN_EMAIL'));
        }
        return $this->client;
    }

    // ─── Get Classroom Service ───────────────────────────────
    private function getService(): Classroom
    {
        if (null === $this->classroomService) {
            $this->classroomService = new Classroom($this->getClient());
        }
        return $this->classroomService;
    }

    // ─── Create Course ───────────────────────────────────────
    public function createCourse(string $name, string $description = '', string $section = ''): ?array
    {
        try {
            $course = new Course([
                'name'        => $name,
                'section'     => $section,
                'description' => $description,
                'ownerId'     => 'me',
                'courseState' => 'ACTIVE',
            ]);

            $created = $this->getService()->courses->create($course);

            return [
                'classroom_id'    => $created->getId(),
                'enrollment_code' => $created->getEnrollmentCode(),
                'alternate_link'  => $created->getAlternateLink(),
            ];

        } catch (\Exception $e) {
            Log::error('Classroom create failed: ' . $e->getMessage());
            return null;
        }
    }

    // ─── Post Announcement ───────────────────────────────────
    public function postAnnouncement(string $courseId, string $text): bool
    {
        try {
            $announcement = new Announcement([
                'text'  => $text,
                'state' => 'PUBLISHED',
            ]);

            $this->getService()->courses_announcements->create($courseId, $announcement);
            return true;

        } catch (\Exception $e) {
            Log::error('Announcement failed: ' . $e->getMessage());
            return false;
        }
    }

    // ─── Enroll Student ──────────────────────────────────────
    public function enrollStudent(string $courseId, string $email): bool
    {
        try {
            $student = new Student([
                'userId' => $email,
            ]);

            $this->getService()->courses_students->create($courseId, $student);
            return true;

        } catch (\Exception $e) {
            Log::error('Enroll failed: ' . $e->getMessage());
            return false;
        }
    }

    // ─── Archive Course ──────────────────────────────────────
    public function archiveCourse(string $courseId): bool
    {
        try {
            $course = new Course([
                'courseState' => 'ARCHIVED',
            ]);

            $this->getService()->courses->patch($courseId, $course, [
                'updateMask' => 'courseState'
            ]);
            return true;

        } catch (\Exception $e) {
            Log::error('Archive failed: ' . $e->getMessage());
            return false;
        }
    }
}