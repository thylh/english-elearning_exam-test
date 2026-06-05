<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function index(Request $request)
    {
        // Fetch published practice exams grouped by skill.
        // Accept either explicitly typed practice exams or force-published exams
        // where subtype and skill are already assigned.
        $exams = Exam::where('published', true)
            ->where(function ($query) {
                $query->where('type', 'practice')
                    ->orWhere(function ($query) {
                        $query->whereNull('type')
                            ->whereNotNull('subtype')
                            ->whereNotNull('skill');
                    });
            })
            ->whereNotNull('skill')
            ->whereNotNull('subtype')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('skill');

        return view('ielts.practice', ['examsBySkill' => $exams]);
    }
}
