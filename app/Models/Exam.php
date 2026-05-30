<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Exam extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'band', 'description', 'duration_minutes', 'published', 'user_id'
    ];

    /**
     * Creator relationship (optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
