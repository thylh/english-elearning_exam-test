<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $exam_id
 * @property int $question_id
 * @property int|null $user_id
 * @property string $type  'writing' or 'speaking'
 * @property string|null $answer_text
 * @property string|null $audio_path
 * @property string $grading_method  'auto' or 'manual'
 * @property float|null $auto_score
 * @property string|null $auto_feedback
 * @property float|null $manual_score
 * @property string|null $manual_feedback
 * @property string $status  'pending' or 'graded'
 * @property int|null $graded_by
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\ExamQuestion $examQuestion
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $gradedBy
 * @mixin \Eloquent
 */
class Submission extends Model
{
    protected $fillable = [
        'exam_id',
        'question_id',
        'user_id',
        'result_id',
        'type',
        'answer_text',
        'audio_path',
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
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function isWriting(): bool
    {
        return $this->type === 'writing';
    }

    public function isSpeaking(): bool
    {
        return $this->type === 'speaking';
    }
}
