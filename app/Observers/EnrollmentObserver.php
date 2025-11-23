<?php

namespace App\Observers;

use App\Models\Enrollment;
use App\Services\CertificateService;

class EnrollmentObserver
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Handle the Enrollment "updated" event.
     */
    public function updated(Enrollment $enrollment): void
    {
        // Check if status changed to completed
        if ($enrollment->isDirty('status') && $enrollment->status === 'completed') {
            // Auto-issue certificate if eligible
            $this->certificateService->autoIssueCertificate(
                $enrollment->user,
                $enrollment->course
            );

            // TODO: Send notification
            // Notification::send($enrollment->user, new CourseCompletedNotification($enrollment->course));
        }
    }
}
