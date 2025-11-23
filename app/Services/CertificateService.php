<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Generate a certificate for a user upon course completion.
     */
    public function generateCertificate(User $user, Course $course): Certificate
    {
        // Check if user has completed the course
        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if certificate already exists
        $existing = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Generate certificate
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $this->generateCertificateNumber(),
            'verification_code' => $this->generateVerificationCode(),
            'issued_at' => now(),
        ]);

        // Generate PDF (placeholder - requires PDF library)
        $certificate->pdf_path = $this->generatePdf($certificate);
        $certificate->save();

        // Update enrollment
        $enrollment->update([
            'certificate_issued_at' => now(),
        ]);

        return $certificate;
    }

    /**
     * Generate unique certificate number.
     */
    private function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Y');
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Generate verification code.
     */
    private function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while (Certificate::where('verification_code', $code)->exists());

        return $code;
    }

    /**
     * Generate PDF certificate.
     * 
     * This is a placeholder. In production, use libraries like:
     * - barryvdh/laravel-dompdf
     * - barryvdh/laravel-snappy
     */
    private function generatePdf(Certificate $certificate): string
    {
        $filename = "certificates/{$certificate->certificate_number}.pdf";
        
        // TODO: Implement PDF generation
        // Example with DomPDF:
        // $pdf = PDF::loadView('certificates.template', [
        //     'certificate' => $certificate,
        //     'user' => $certificate->user,
        //     'course' => $certificate->course,
        // ]);
        // Storage::put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Verify a certificate by verification code.
     */
    public function verifyCertificate(string $code): ?Certificate
    {
        return Certificate::with(['user', 'course'])
            ->where('verification_code', $code)
            ->first();
    }

    /**
     * Check if user is eligible for certificate.
     */
    public function isEligibleForCertificate(User $user, Course $course): bool
    {
        // Check if course has certificates enabled
        if (!$course->certificate_enabled) {
            return false;
        }

        // Check enrollment
        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return false;
        }

        // Check if completed
        if ($enrollment->status !== 'completed') {
            return false;
        }

        // Check if passing percentage met
        if ($enrollment->progress < $course->passing_percentage) {
            return false;
        }

        return true;
    }

    /**
     * Automatically issue certificate when course is completed.
     */
    public function autoIssueCertificate(User $user, Course $course): ?Certificate
    {
        if (!$this->isEligibleForCertificate($user, $course)) {
            return null;
        }

        return $this->generateCertificate($user, $course);
    }
}
