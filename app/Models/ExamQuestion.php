<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $exam_id
 * @property string|null $section_skill
 * @property int|null $part_number
 * @property string $question_text
 * @property string $question_type
 * @property array<array-key, mixed>|null $options
 * @property string|null $correct_answer
 * @property string|null $explanation
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion wherePartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereSectionSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'section_skill',
        'part_number',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}