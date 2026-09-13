<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        if ($lesson->module->course->trainer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,document,link,other',
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        $path = $request->file('file')->store('public/learning_resources');

        $lesson->resources()->create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => Storage::url($path),
        ]);

        return back()->with('success', 'Resource uploaded successfully.');
    }
}
