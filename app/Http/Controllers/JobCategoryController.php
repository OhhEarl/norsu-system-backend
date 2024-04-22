<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{

    public function index()
    {
        try {
            $jobCategories = JobCategory::all();

            return response()->json($jobCategories);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching job categories', 'error' => $e->getMessage()], 500);
        }
    }
}
