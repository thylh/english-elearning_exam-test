<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function serveExamQuestion(Request $request, ExamQuestion $question)
    {
        // Optional: add auth/permission checks here
        // abort_unless(auth()->check(), 403);

        if (! $question->prompt_attachment) {
            abort(404);
        }

        $disk = Storage::disk('local');
        $path = ltrim($question->prompt_attachment, '/');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $size = filesize($fullPath);
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        $start = 0;
        $length = $size;
        $status = 200;
        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
        ];

        if ($request->headers->has('range')) {
            $range = $request->header('range');
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = $matches[2] === '' ? ($size - 1) : intval($matches[2]);
                if ($end > $size - 1) $end = $size - 1;
                $length = $end - $start + 1;
                $status = 206;
                $headers['Content-Range'] = "bytes $start-$end/$size";
                $headers['Content-Length'] = $length;
            }
        } else {
            $headers['Content-Length'] = $size;
        }

        $response = new StreamedResponse(function () use ($fullPath, $start, $length) {
            $fp = fopen($fullPath, 'rb');
            fseek($fp, $start);
            $bufferSize = 1024 * 8;
            $remaining = $length;
            while ($remaining > 0 && !feof($fp)) {
                $read = ($remaining > $bufferSize) ? $bufferSize : $remaining;
                echo fread($fp, $read);
                flush();
                $remaining -= $read;
            }
            fclose($fp);
        }, $status, $headers);

        return $response;
    }
}
