<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Lesson $lesson)
    {
        $lesson->load(['module.course', 'questions', 'comments.user']);

        // Check if user is enrolled
        $user = Auth::user();
        $enrolled = $user->enrollments()
            ->where('course_id', $lesson->module->course_id)
            ->exists();

        if (!$enrolled && !$lesson->is_free_preview) {
            return response()->json(['message' => 'Not enrolled in this course'], 403);
        }

        // Get user progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => 'not_started',
                'progress_percentage' => 0,
            ]
        );

        return response()->json([
            'lesson' => $lesson,
            'progress' => $progress,
        ]);
    }

    /**
     * Start a lesson.
     */
    public function start(Lesson $lesson)
    {
        $user = Auth::user();

        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => 'in_progress',
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]
        );

        return response()->json($progress);
    }

    /**
     * Complete a lesson.
     */
    public function complete(Lesson $lesson)
    {
        $user = Auth::user();

        $progress = UserProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);

        return response()->json($progress);
    }

    /**
     * Update progress percentage.
     */
    public function updateProgress(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'time_spent' => 'nullable|integer',
        ]);

        $user = Auth::user();

        $progress = UserProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->update([
            'progress_percentage' => $validated['progress_percentage'],
            'time_spent' => $progress->time_spent + ($validated['time_spent'] ?? 0),
            'last_accessed_at' => now(),
        ]);

        return response()->json($progress);
    }

    /**
     * Submit answers for a quiz lesson.
     */
    public function submitAnswers(Request $request, Lesson $lesson)
    {
        if ($lesson->type !== 'quiz') {
            return response()->json(['message' => 'Not a quiz lesson'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required',
        ]);

        $user = Auth::user();
        $totalPoints = 0;
        $earnedPoints = 0;

        // Check attempt limits
        $progress = UserProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($lesson->max_attempts && $progress && $progress->attempts >= $lesson->max_attempts) {
            return response()->json(['message' => 'Maximum attempts exceeded'], 400);
        }

        foreach ($validated['answers'] as $answerData) {
            $question = $lesson->questions()->findOrFail($answerData['question_id']);
            $totalPoints += $question->points;

            // Simple answer checking (can be expanded)
            $isCorrect = $this->checkAnswer($question, $answerData['answer']);
            $points = $isCorrect ? $question->points : 0;
            $earnedPoints += $points;

            // Save user answer
            $user->answers()->create([
                'question_id' => $question->id,
                'lesson_id' => $lesson->id,
                'answer' => $answerData['answer'],
                'is_correct' => $isCorrect,
                'points_earned' => $points,
            ]);
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        // Update progress
        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => $score >= ($lesson->passing_score ?? 70) ? 'completed' : 'in_progress',
                'progress_percentage' => 100,
                'score' => $score,
                'attempts' => ($progress->attempts ?? 0) + 1,
                'completed_at' => $score >= ($lesson->passing_score ?? 70) ? now() : null,
            ]
        );

        return response()->json([
            'score' => $score,
            'earned_points' => $earnedPoints,
            'total_points' => $totalPoints,
            'passed' => $score >= ($lesson->passing_score ?? 70),
            'progress' => $progress,
        ]);
    }

    /**
     * Check if answer is correct.
     */
    private function checkAnswer($question, $answer)
    {
        // This is a simple implementation
        // Can be expanded based on question type
        switch ($question->type) {
            case 'single_choice':
            case 'true_false':
                return $answer === $question->correct_answer[0] ?? false;
            
            case 'multiple_choice':
                sort($answer);
                $correct = $question->correct_answer;
                sort($correct);
                return $answer === $correct;
            
            case 'short_answer':
                return strtolower(trim($answer)) === strtolower(trim($question->correct_answer[0] ?? ''));
            
            default:
                return false;
        }
    }
}
