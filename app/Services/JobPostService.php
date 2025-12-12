<?php

namespace App\Services;

use App\Repositories\JobPostRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class JobPostService
{
    protected $repository;

    public function __construct(JobPostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPublishedJobs()
    {
        return $this->repository->getPublished();
    }

    public function createJob(array $data)
    {
        $data['employer_id'] = Auth::id();

        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->repository->create($data);
    }

    public function updateJob($id, array $data)
    {
        $job = $this->repository->findById($id);

        if (!$job) {
            throw new Exception("Job not found", 404);
        }

        if ($job->employer_id !== Auth::id()) {
            throw new Exception("Unauthorized: You do not own this job post", 403);
        }

        return $this->repository->update($job, $data);
    }

    public function getJobApplications($id)
    {
        $job = $this->repository->getJobWithApplications($id);

        if (!$job) {
            throw new Exception("Job not found", 404);
        }

        if ($job->employer_id !== Auth::id()) {
            throw new Exception("Unauthorized", 403);
        }

        return $job->applications;
    }
}