<?php

namespace App\Http\Controllers;

use App\Services\JobPostService;
use Illuminate\Http\Request;
use Exception;

class JobPostController extends Controller
{
    protected $service;

    public function __construct(JobPostService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/jobs
     * Freelancer: View list of published jobs
     */
    public function index()
    {
        $jobs = $this->service->getAllPublishedJobs();
        return response()->json(['status' => 'success', 'data' => $jobs]);
    }

    /**
     * POST /api/jobs
     * Employer: Create a new job
     */
    public function store(Request $request)
    {
        // Basic Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'in:draft,published' // Optional, defaults to draft in Service
        ]);

        $job = $this->service->createJob($validated);

        return response()->json([
            'status' => 'success', 
            'message' => 'Job created successfully', 
            'data' => $job
        ], 201);
    }

    /**
     * PUT /api/jobs/{id}
     * Employer: Update job details or publish it
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'in:draft,published'
        ]);

        try {
            $job = $this->service->updateJob($id, $validated);
            return response()->json(['status' => 'success', 'data' => $job]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    /**
     * GET /api/jobs/{id}/applications
     * Employer: See who applied
     */
    public function getApplications($id)
    {
        try {
            $applications = $this->service->getJobApplications($id);
            return response()->json(['status' => 'success', 'data' => $applications]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}