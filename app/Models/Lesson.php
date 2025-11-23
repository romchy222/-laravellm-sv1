<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_module_id',
        'title',
        'description',
        'type',
        'content',
        'video_url',
        'video_duration',
        'document_url',
        'order',
        'is_published',
        'is_free_preview',
        'passing_score',
        'time_limit',
        'max_attempts',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_free_preview' => 'boolean',
            'content' => 'array',
        ];
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function comments()
    {
        return $this->hasMany(LessonComment::class);
    }
}
