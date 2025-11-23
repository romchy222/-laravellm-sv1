<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get current user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['roles', 'groups', 'achievements', 'wallet']);

        // Get enrolled courses with progress
        $enrollments = $user->enrollments()
            ->with('course')
            ->get()
            ->map(function($enrollment) {
                return [
                    'course' => $enrollment->course,
                    'progress' => $enrollment->progress,
                    'status' => $enrollment->status,
                    'enrolled_at' => $enrollment->enrolled_at,
                ];
            });

        // Get progress statistics
        $totalLessons = $user->progress()->count();
        $completedLessons = $user->progress()->where('status', 'completed')->count();
        $totalPoints = $user->achievements()->sum('points');

        return response()->json([
            'user' => $user,
            'enrollments' => $enrollments,
            'statistics' => [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'total_points' => $totalPoints,
                'total_achievements' => $user->achievements()->count(),
            ],
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string',
            'timezone' => 'sometimes|string',
            'locale' => 'sometimes|string|max:10',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Get user's enrolled courses.
     */
    public function enrolledCourses()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
            ->with('course.instructor')
            ->get()
            ->map(function($enrollment) use ($user) {
                // Calculate course progress
                $lessons = $enrollment->course->modules()
                    ->with('lessons')
                    ->get()
                    ->pluck('lessons')
                    ->flatten();

                $totalLessons = $lessons->count();
                $completedLessons = $user->progress()
                    ->whereIn('lesson_id', $lessons->pluck('id'))
                    ->where('status', 'completed')
                    ->count();

                $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

                return [
                    'course' => $enrollment->course,
                    'progress' => round($progress, 2),
                    'status' => $enrollment->status,
                    'enrolled_at' => $enrollment->enrolled_at,
                ];
            });

        return response()->json($enrollments);
    }

    /**
     * Get user's achievements.
     */
    public function achievements()
    {
        $user = Auth::user();

        $achievements = $user->achievements()
            ->withPivot('earned_at')
            ->get();

        return response()->json($achievements);
    }

    /**
     * Get user's certificates.
     */
    public function certificates()
    {
        $user = Auth::user();

        $certificates = $user->certificates()
            ->with('course')
            ->get();

        return response()->json($certificates);
    }

    /**
     * Get user's wallet balance.
     */
    public function wallet()
    {
        $user = Auth::user();

        $wallet = $user->wallet()->with('transactions')->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'USD',
        ]);

        return response()->json($wallet);
    }
}
