<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine if the user can view any courses.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        // Published courses are visible to all
        if ($course->is_published) {
            return true;
        }

        // Unpublished courses only visible to instructor and admins
        return $user->id === $course->instructor_id || $user->hasRole('admin');
    }

    /**
     * Determine if the user can create courses.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['teacher', 'admin']);
    }

    /**
     * Determine if the user can update the course.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id || $user->hasRole('admin');
    }

    /**
     * Determine if the user can delete the course.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id || $user->hasRole('admin');
    }

    /**
     * Determine if the user can publish the course.
     */
    public function publish(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id || $user->hasRole('admin');
    }

    /**
     * Determine if the user can enroll in the course.
     */
    public function enroll(User $user, Course $course): bool
    {
        // Must be published
        if (!$course->is_published) {
            return false;
        }

        // Cannot enroll if already enrolled
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return false;
        }

        return true;
    }
}
