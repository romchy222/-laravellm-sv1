<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category'])
            ->where('is_published', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by level
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->paginate($request->get('per_page', 15));

        return response()->json($courses);
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'format' => 'required|in:self_paced,cohort,blended',
            'language' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'duration' => 'nullable|integer',
            'max_students' => 'nullable|integer',
            'drip_content' => 'boolean',
            'certificate_enabled' => 'boolean',
            'passing_percentage' => 'required|integer|min:0|max:100',
        ]);

        $validated['instructor_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = 'draft';

        $course = Course::create($validated);

        return response()->json($course, 201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['instructor', 'category', 'modules.lessons', 'reviews']);

        return response()->json($course);
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'level' => 'sometimes|in:beginner,intermediate,advanced',
            'format' => 'sometimes|in:self_paced,cohort,blended',
            'language' => 'sometimes|string|max:10',
            'price' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'duration' => 'nullable|integer',
            'max_students' => 'nullable|integer',
            'drip_content' => 'boolean',
            'certificate_enabled' => 'boolean',
            'passing_percentage' => 'sometimes|integer|min:0|max:100',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $course->update($validated);

        return response()->json($course);
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }

    /**
     * Publish a course.
     */
    public function publish(Course $course)
    {
        $course->update([
            'is_published' => true,
            'published_at' => now(),
            'status' => 'published',
        ]);

        return response()->json($course);
    }

    /**
     * Enroll in a course.
     */
    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Check if already enrolled
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 400);
        }

        // Check if course is full
        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return response()->json(['message' => 'Course is full'], 400);
        }

        $enrollment = $user->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
            'payment_status' => $course->price > 0 ? 'pending' : 'paid',
            'payment_amount' => $course->price,
            'payment_currency' => $course->currency,
        ]);

        return response()->json($enrollment, 201);
    }
}
