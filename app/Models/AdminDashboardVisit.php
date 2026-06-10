<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDashboardVisit extends Model
{
    protected $fillable = [
        'user_id',
        'path',
        'ip_address',
        'user_agent',
    ];
}
