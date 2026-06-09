<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WritingSubmission extends Model
{
    protected $fillable = [
        'exam_id',
        'question_id',
        'user_id',
        'answer_text',
        'grading_method',
        'auto_score',
        'auto_feedback',
        'manual_score',
        'manual_feedback',
        'status',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'auto_score' => 'decimal:2',
        'manual_score' => 'decimal:2',
    ];

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
