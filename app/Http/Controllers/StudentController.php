<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use App\Models\StudentSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentValidation;
use App\Models\StudentValidationsPortfolio;
use App\Models\User;
use Google\Client as Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function googleCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idToken' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $token_id = $request->input('idToken');

        $client = new Google_Client(['client    _id' => '1070570385371-6p351s3v9d1tr5mvrqfqhbe4vnn59mhb.apps.googleusercontent.com']);

        try {
            $payload = $client->verifyIdToken($token_id);

            if (!$payload) {
                return response()->json(['error' => 'Invalid token', 'payload' => $payload], 401);
            }

            $uid = $payload['sub'];
            Log::info('Token payload:', $payload);
            $userData = [
                'name' => $payload['name'],
                'email' => $payload['email'],
            ];

            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                // User does not exist, create a new user
                $user = User::create($userData);
            }
            $token = $user->createToken('myapp')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        }
    }


    public function googleCallbackSignOut(Request $request)
    {
        $user = $request->user();

        if ($user) {

            $user->tokens()->delete();

            return response()->json(['message' => 'All tokens revoked for the user'], 200);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }


    public function registerEmailPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = auth()->user();

            $token = $user->createToken('myapp')->plainTextToken;
            return response()->json(['message' => 'User logged in successfully', 'user' => $user, 'token' => $token], 200);
        }

        // If authentication fails and the user doesn't exist, create a new user
        $user = User::firstOrNew(['email' => $request->email]);
        if (!$user->exists) {
            $user->password = bcrypt($request->password);
            $user->save();
            // Authenticate the newly created user
            auth()->login($user);

            $token = $user->createToken('myapp')->plainTextToken;
            return response()->json(['message' => 'New user registered and logged in successfully', 'user' => $user, 'token' => $token], 200);
        }

        // Authentication failed, return an error
        return response()->json(['message' => 'Invalid credentials'], 401);
    }




    public function studentValidation(Request $request)
    {
        $request->validate([
            'imageFront' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imageBack' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'userName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'areaOfExpertise' => 'required|string|max:255',
            'norsuIDnumber' => 'required|string|max:255',
            'yearLevel' => 'required|int|max:255',
            'user_id' => 'required',
        ]);
        $existingValidation = StudentValidation::where('user_id', $request->input('user_id'))->first();

        if ($existingValidation) {
            // If a StudentValidation record already exists for the user, return an error response
            return response()->json(['success' => false, 'message' => 'Student already has a validation record'], 400);
        } else {



            $imageFront = $request->file('imageFront')->store('storage/images');
            $imageBack = $request->file('imageBack')->store('storage/images');

            $areaOfExpertise = trim($request->input('areaOfExpertise'));
            $expertise = Expertise::where('expertise', $areaOfExpertise)->first();

            if ($expertise) {
                $areaOfExpertiseId = $expertise->id;
            } else {
                // If the area of expertise doesn't exist, create a new one
                $newExpertise = Expertise::create([
                    'expertise' => $areaOfExpertise,
                ]);

                $areaOfExpertiseId = $newExpertise->id;
            }




            $data = [
                'user_id' => $request->input('user_id'),
                'first_name' => $request->input('firstName'),
                'last_name' => $request->input('lastName'),
                'user_name' => $request->input('userName'),
                'area_of_expertise' =>  $areaOfExpertiseId,
                'norsu_id_number' => $request->input('norsuIDnumber'),
                'course' => $request->input('course'),
                'year_level' => $request->input('yearLevel'),
                'front_id' => $imageFront,
                'back_id' => $imageBack,
            ];



            $studentCreate =  StudentValidation::create($data);
            if ($request->has('student_skills')) {

                if (!empty($request->input('student_skills'))) {

                    $studentValidationId = $studentCreate->id; // Assuming $studentCreate is an instance of StudentValidation

                    foreach ($request->input('student_skills') as $skillTag) {

                        $skill = new StudentSkills([
                            'user_id' => $studentValidationId,
                            'student_skills' => $skillTag,
                        ]);
                        $studentCreate->studentSkills()->save($skill);
                    }
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Student Data Saved Successfully'], 200);
    }


    public function studentValidationUpdate(Request $request)
    {

        Log::info($request);
        $request->validate([
            'student_user_id' => 'required',
            'user_name' => 'required|string|max:255',
            'area_of_expertise' => 'required|string|max:255',
            'about_me' => 'nullable|string|max:255',
            'student_skills.*' => 'required|string|max:255',
            'user_avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $studentValidation = StudentValidation::where('user_id', $request->student_user_id)->first();

        if (!$studentValidation) {
            return response()->json(['message' => 'Student validation not found'], 404);
        }


        $areaOfExpertise = trim($request->input('area_of_expertise'));
        $expertise = Expertise::where('expertise', $areaOfExpertise)->first();

        if ($expertise) {
            $areaOfExpertiseId = $expertise->id;
        } else {
            // If the area of expertise doesn't exist, create a new one
            $newExpertise = Expertise::create([
                'expertise' => $areaOfExpertise,
            ]);

            $areaOfExpertiseId = $newExpertise->id;
        }

        $studentValidation->user_name = $request->user_name;
        $studentValidation->area_of_expertise = $areaOfExpertiseId;
        $studentValidation->about_me = $request->about_me;

        // Handle student skills
        if ($request->has('student_skills')) {
            $studentValidation->studentSkills()->delete(); // Remove existing skills
            foreach ($request->student_skills as $skill) {
                $newSkill = new StudentSkills([
                    'student_skills' => $skill,
                ]);
                $studentValidation->studentSkills()->save($newSkill);
            }
        }


        if ($request->hasFile('user_avatar')) {
            // Delete old avatar if it exists
            if ($studentValidation->user_avatar) {
                // Remove 'storage/' prefix to get the relative path
                $oldAvatarPath = str_replace('storage/', 'public/', $studentValidation->user_avatar);
                // Delete the old avatar
                Storage::delete($oldAvatarPath);
            }
            // Store new avatar
            $avatarPath = $request->file('user_avatar')->store('public/avatar');
            // Update user_avatar field
            $studentValidation->user_avatar = str_replace('public/', 'storage/', $avatarPath);
            // Save the model
            $studentValidation->save();
        }

        if ($request->hasFile('portfolio')) {

            Log::info($request->hasFile('portfolio'));
            foreach ($request->file('portfolio') as $file) {
                $path = $file->store('public/portfolio');
                $portfolio = new StudentValidationsPortfolio([
                    'student_portfolio_path' => str_replace('public/', 'storage/', $path),
                ]);
                $studentValidation->studentPortfolio()->save($portfolio);
            }

            $isUpdated = $studentValidation->save();

            if ($isUpdated) {
                return response()->json(['message' => 'User updated successfully', 'status' => 200], 200);
            } else {
                return response()->json(['message' => 'Failed to update user', 'status' => 500], 500);
            }
        }
    }


    public function index()
    {
        $students = StudentValidation::all();
        return view('pages.user-management.user-management', [
            'students' => $students
        ]);
    }


    public function accept($id)
    {

        $is_student = StudentValidation::where('id', $id)->first();

        if ($is_student) {
            $is_student->update(['is_student' => 1]);
            return redirect()->back()->with('success', 'Student accepted successfully!');
        } else {
            // Handle the case where the StudentValidation record with the given ID is not found
            return redirect()->back()->with('error', 'Student not found.');
        }
    }

    public function cancel($id)
    {
        $is_student = StudentValidation::findOrFail($id);
        $is_student->update(['status' => 0]); // Assuming 'status' is the field representing approval status
        return redirect()->back()->with('success', 'Leave cancelled successfully!');
    }


    public function fetchUser(Request $request)
    {

        $studentValidation = StudentValidation::where('user_id', $request->user()->id)->first();
        $expertise = Expertise::find($studentValidation->area_of_expertise);
        $areaOfExpertise = $expertise ? $expertise->expertise : null;
        $skills = $studentValidation->studentSkills->pluck('student_skills')->toArray();
        $avatarUrl = url("{$studentValidation->user_avatar}");
        $token = $request->bearerToken();
        if ($token) {
            return response()->json([
                "studentInfo" => [
                    "area_of_expertise" => $areaOfExpertise,
                    "back_id" => $studentValidation->back_id,
                    "course" => $studentValidation->course,
                    "created_at" => $studentValidation->created_at,
                    "first_name" => $studentValidation->first_name,
                    "front_id" => $studentValidation->front_id,
                    "id" => $studentValidation->id,
                    "is_student" => $studentValidation->is_student,
                    "last_name" => $studentValidation->last_name,
                    "norsu_id_number" => $studentValidation->norsu_id_number,
                    "updated_at" => $studentValidation->updated_at,
                    "user_avatar" => $avatarUrl,
                    "user_id" => $studentValidation->user_id,
                    "user_name" => $studentValidation->user_name,
                    "year_level" => $studentValidation->year_level,
                    "about_me" => $studentValidation->about_me,
                    "skill_tags" => $skills
                ],
                "token" => $token
            ], 200); // Success (200)
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }
    }

    public function getStudentPortfolio($studentUserId)
    {
        try {
            $studentValidation = StudentValidation::where('user_id', $studentUserId)->first();
            if (!$studentValidation) {
                return response()->json(['message' => 'Student validation not found'], 404);
            }

            // Generate URLs for portfolio images
            $portfolioURLs = [];
            foreach ($studentValidation->studentPortfolio as $portfolio) {
                $url = url($portfolio->student_portfolio_path);
                $portfolioURLs[] = $url;
            }

            return response()->json(['portfolio' => $portfolioURLs], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching portfolio', 'error' => $e->getMessage()], 500);
        }
    }
}
