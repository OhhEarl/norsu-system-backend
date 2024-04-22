<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:create_jobs,id',
            'freelancer_id' => 'required|exists:student_validations,id',
            'user_id' => 'required|exists:student_validations,id',
            'job_title' => 'required|string',
            'expertise_explain' => 'required|string',
            'due_date' => 'nullable|date_format:Y-m-d',
            'job_amount_bid' => 'required|numeric',
            'status' => 'boolean',

        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $proposal = Proposal::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $proposal,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'project_id' => 'exists:create_jobs,id',
            'freelancer_id' => 'exists:student_validations,id',
            'user_id' => 'exists:student_validations,id',
            'job_title' => 'string',
            'expertise_explain' => 'string',
            'due_date' => 'nullable|date_format:Y-m-d',
            'job_amount_bid' => 'numeric',
            'status' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        // Find the proposal
        $proposal = Proposal::find($id);

        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found',
            ], 404);
        }

        // Update the proposal
        $proposal->update($request->all());

        // Fetch the related job
        $proposal = Proposal::with('jobProposal')->where('freelancer_id', $request->freelancer_id)->get();
        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found',
            ], 404);
        }

        // Return the updated proposal and related job data
        return response()->json([
            'success' => true,
            'data' => [
                'proposal' => $proposal,
            ],
        ], 200);
    }

    public function show($userID)
    {
        try {
            $proposal = Proposal::with('jobProposal')->where('freelancer_id', $userID)->get();
            return response()->json([
                'success' => true,
                'data' => $proposal,
            ]);;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching proposal', 'error' => $e->getMessage()], 500);
        }
    }

    public function projectProposal($projectId)
    {
        try {
            // Fetch proposals for the given project ID with associated freelancer information and job tags
            $proposals = Proposal::with([
                'freelancer',
                'freelancer.expertise',
                'freelancer.yearLevel',
                'freelancer.studentPortfolio',
                'jobProposal.job_tags',  // Load job tags through the CreateJob model
            ])
                ->where('project_id', $projectId)
                ->get();

            // Replace the area_of_expertise foreign key with the actual expertise value
            // Replace the year_level foreign key with the actual year level value
            $proposals->transform(function ($proposal) {
                $proposal->freelancer->area_of_expertise = $proposal->freelancer->expertise->expertise;
                $proposal->freelancer->year_level = $proposal->freelancer->yearLevel->year_level;
                $proposal->freelancer->portfolio = $proposal->freelancer->studentPortfolio;
                $proposal->freelancer->student_tags = $proposal->freelancer->studentSkills;

                // Extract project tags from the CreateJob relationship


                // Extract job tags from the jobProposal relationship
                if ($proposal->jobProposal) {
                    $proposal->job_tags = $proposal->jobProposal->job_tags;
                } else {
                    $proposal->job_tags = [(object) []];  // Return an array containing an empty object
                }

                unset($proposal->freelancer->expertise);
                unset($proposal->freelancer->yearLevel);
                unset($proposal->freelancer->studentPortfolio);
                unset($proposal->createJob);  // Remove the createJob attribute
                unset($proposal->jobProposal);  // Remove the jobProposal attribute

                return $proposal;
            });


            return response()->json([
                'success' => true,
                'data' => $proposals,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching proposals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
