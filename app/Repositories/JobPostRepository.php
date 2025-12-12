<?php

namespace App\Repositories;

use App\Models\JobPost;

class JobPostRepository
{
    public function getPublished()
    {
        return JobPost::with('employer:id,name')
            ->where('status', 'published')
            ->latest()
            ->get();
    }

    // For Employers: Create a new job
    public function create(array $data)
    {
        return JobPost::create($data);
    }

    public function findById($id)
    {
        return JobPost::find($id);
    }

    public function update(JobPost $jobPost, array $data)
    {
        $jobPost->update($data);
        return $jobPost;
    }

    public function getJobWithApplications($id)
    {
        return JobPost::with(['applications.freelancer:id,name,email'])->find($id);
    }
}