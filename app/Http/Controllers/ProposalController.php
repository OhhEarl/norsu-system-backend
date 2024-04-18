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
            'user_id' => 'required|exists:student_validations,id',
            'freelancer_id' => 'required|exists:student_validations,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'boolean',
            'due_date' => 'nullable|date_format:Y-m-d',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $proposal = Proposal::create($request->all());

        // Fetch related data


        return response()->json([
            'success' => true,
            'data' => [
                'proposal' => $proposal,

            ],
            'message' => 'Proposal created successfully.',
        ]);
    }

    public function show($userID)
    {
        try {
            $proposal = Proposal::where('freelancer_id', $userID)->first();
            return response()->json([
                'success' => true,
                'data' => $proposal,
            ]);;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching proposal', 'error' => $e->getMessage()], 500);
        }
    }
}
