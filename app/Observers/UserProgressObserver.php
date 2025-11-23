<?php

namespace App\Observers;

use App\Models\UserProgress;
use App\Models\Achievement;

class UserProgressObserver
{
    /**
     * Handle the UserProgress "updated" event.
     */
    public function updated(UserProgress $progress): void
    {
        // Check if lesson was just completed
        if ($progress->isDirty('status') && $progress->status === 'completed') {
            $user = $progress->user;

            // Check for achievements
            $this->checkAchievements($user);

            // Update enrollment progress
            $this->updateEnrollmentProgress($progress);
        }
    }

    /**
     * Check and award achievements.
     */
    private function checkAchievements($user): void
    {
        // Get user's completed lessons count
        $completedLessons = $user->progress()
            ->where('status', 'completed')
            ->count();

        // Define achievement thresholds
        $thresholds = [
            5 => 'first-5-lessons',
            10 => 'first-10-lessons',
            25 => 'first-25-lessons',
            50 => 'first-50-lessons',
            100 => 'first-100-lessons',
        ];

        foreach ($thresholds as $threshold => $achievementSlug) {
            if ($completedLessons >= $threshold) {
                $achievement = Achievement::where('type', 'course_completion')
                    ->where('requirement->lessons', $threshold)
                    ->first();

                if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                    $user->achievements()->attach($achievement->id, [
                        'earned_at' => now(),
                    ]);

                    // TODO: Send notification
                    // Notification::send($user, new AchievementEarnedNotification($achievement));
                }
            }
        }
    }

    /**
     * Update enrollment progress percentage.
     */
    private function updateEnrollmentProgress(UserProgress $progress): void
    {
        $lesson = $progress->lesson;
        $course = $lesson->module->course;
        $user = $progress->user;

        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return;
        }

        // Calculate overall course progress
        $lessons = $course->modules()
            ->with('lessons')
            ->get()
            ->pluck('lessons')
            ->flatten();

        $totalLessons = $lessons->count();
        if ($totalLessons === 0) {
            return;
        }

        $completedLessons = $user->progress()
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->where('status', 'completed')
            ->count();

        $progressPercentage = ($completedLessons / $totalLessons) * 100;

        // Update enrollment
        $enrollment->update([
            'progress' => round($progressPercentage, 2),
            'status' => $progressPercentage >= 100 ? 'completed' : 'active',
            'completed_at' => $progressPercentage >= 100 ? now() : null,
        ]);
    }
}
