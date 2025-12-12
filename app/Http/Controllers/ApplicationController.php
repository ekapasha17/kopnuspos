<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Max 2MB
        ]);

        $user = Auth::user();

        // 2. Role Check: Only Freelancers can apply
        if ($user->role !== 'freelancer') {
            return response()->json(['message' => 'Only freelancers can apply'], 403);
        }

        // 3. Logic: Check if Job is actually Published (Cannot apply to drafts)
        $job = JobPost::find($request->job_post_id);
        if ($job->status !== 'published') {
            return response()->json(['message' => 'This job is not open for applications'], 400);
        }

        // 4. Duplicate Check
        $exists = Application::where('job_post_id', $request->job_post_id)
                             ->where('freelancer_id', $user->id)
                             ->exists();

        if ($exists) {
            return response()->json(['message' => 'You have already applied for this job'], 409);
        }

        // 5. File Upload Logic
        if ($request->hasFile('cv_file')) {
            // Stores file in 'storage/app/public/cvs'
            $path = $request->file('cv_file')->store('cvs', 'public');
        }

        // 6. Save to DB
        $application = Application::create([
            'job_post_id' => $request->job_post_id,
            'freelancer_id' => $user->id,
            'cv_path' => $path,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully',
            'data' => $application
        ], 201);
    }
}