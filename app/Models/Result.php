<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int $exam_id
 * @property float|null $score
 * @property int|null $correct_answers
 * @property int $total_questions
 * @property string $status  'pending' or 'graded'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Submission> $submissions
 */
class Result extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'score',
        'correct_answers',
        'total_questions',
        'status',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
