<?php

namespace App\Http\Controllers;

use App\Models\CreateJob;
use App\Models\JobAttachment;
use App\Models\JobTag;
use App\Models\ProjectAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CreateJobController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_user_id' => 'required|exists:student_validations,id',
                'job_title' => 'required|string',
                'job_category_id' => 'required|exists:job_categories,id',
                'job_tags' => 'required|array',
                'job_tags.*' => 'required|string',
                'job_description' => 'required|string',
                'job_start_date' => 'required|date',
                'job_end_date' => 'required|date',
                'job_budget_from' => 'required|numeric',
                'job_budget_to' => 'required|numeric',
                'attachments.*' => 'required|file|max:20000|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,mov,ogg,qt,pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
                'job_finished' => 'nullable|boolean',
            ]);

            $job = CreateJob::create([
                'student_user_id' => $request->student_user_id,
                'job_title' => $request->job_title,
                'job_category_id' => $request->job_category_id,
                'job_description' => $request->job_description,
                'job_start_date' => $request->job_start_date,
                'job_end_date' => $request->job_end_date,
                'job_budget_from' => $request->job_budget_from,
                'job_budget_to' => $request->job_budget_to,
            ]);

            $this->saveJobTags($request, $job);
            $this->saveJobAttachments($request, $job);

            return response()->json(['message' => 'Job created successfully'], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // User is authenticated, proceed to fetch jobs with related category name
        $jobs = CreateJob::with(['attachments', 'job_tags', 'job_category'])->get()->map(function ($job) {
            $job->category_name = $job->job_category->value; // Assuming 'name' is the column that contains the category name
            unset($job->job_category_id, $job->job_category); // Remove the ID and the related object

            return $job;
        });

        // Return the jobs as JSON response
        return response()->json(['jobs' => $jobs], 200);
    }

    public function show($userID)
    {
        try {
            $jobCreated = CreateJob::with('job_category', 'attachments', 'job_tags')->where('student_user_id', $userID)->get();
            return response()->json([
                'success' => true,
                'data' => $jobCreated,
            ]);;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching proposal', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'student_user_id' => 'required|exists:student_validations,id',
                'job_title' => 'required|string',
                'job_category_id' => 'required|exists:job_categories,id',
                'job_tags' => 'required|array',
                'job_tags.*' => 'required|string',
                'job_description' => 'required|string',
                'job_start_date' => 'required|date',
                'job_end_date' => 'required|date',
                'job_budget_from' => 'required|numeric',
                'job_budget_to' => 'required|numeric',
                'attachments.*' => 'required|file|max:20000|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,mov,ogg,qt,pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
                'job_finished' => 'nullable|boolean',
            ]);

            $job = CreateJob::findOrFail($id);

            $job->update([
                'student_user_id' => $request->student_user_id,
                'job_title' => $request->job_title,
                'job_category_id' => $request->job_category_id,
                'job_description' => $request->job_description,
                'job_start_date' => $request->job_start_date,
                'job_end_date' => $request->job_end_date,
                'job_budget_from' => $request->job_budget_from,
                'job_budget_to' => $request->job_budget_to,
            ]);

            $job->job_tags()->delete();
            $this->saveJobTags($request, $job);
            $job->attachments()->delete();
            $this->saveJobAttachments($request, $job);

            return response()->json(['message' => 'Job updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    private function saveJobTags(Request $request, $job)
    {
        if ($request->has('job_tags')) {
            if (!empty($request->input('job_tags')) && $request->input('job_tags') !== ["Tags"]) {
                foreach ($request->input('job_tags') as $tagId) {
                    $jobTag = new JobTag([
                        'job_tags' => $tagId,
                    ]);
                    $job->job_tags()->save($jobTag);
                }
            }
        }
    }

    private function saveJobAttachments(Request $request, $job)
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachmentFile) {
                $originalName = $attachmentFile->getClientOriginalName();
                $fileType = $attachmentFile->getClientMimeType();
                $directory = match (true) {
                    str_contains($fileType, 'image') => 'attachments/images',
                    str_contains($fileType, 'video') => 'attachments/videos',
                    str_contains($fileType, 'application/pdf') => 'attachments/documents',
                    default => 'attachments/others',
                };

                $attachmentPath = $attachmentFile->store($directory, 'public');
                $attachment = new JobAttachment(['file_path' => $attachmentPath, 'original_name' => $originalName]);
                $job->attachments()->save($attachment);
            }
        }
    }
}
