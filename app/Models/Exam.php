<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExamQuestion;
use App\Models\User;

/**
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string $type
 * @property string|null $subtype For practice: "single" or "full"
 * @property string|null $skill For practice: reading, listening, writing, or speaking
 * @property string|null $category
 * @property string|null $band
 * @property string|null $description
 * @property int|null $duration_minutes
 * @property bool $published
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ExamQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereBand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereSkill($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereSubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereUserId($value)
 * @mixin \Eloquent
 */
class Exam extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'subtype', 'skill', 'category', 'band', 'description', 'duration_minutes', 'published', 'user_id'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * Creator relationship (optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('order');
    }
}
