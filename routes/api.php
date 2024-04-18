<?php

use App\Http\Controllers\CreateJobController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('google-callback/auth/google-signout', [StudentController::class, 'googleCallbackSignOut']);
    Route::post('student-validation', [StudentController::class, 'studentValidation']);
    Route::get('fetch-user-data', [StudentController::class, 'fetchUser']);
    Route::post('student-validations/update', [StudentController::class, 'studentValidationUpdate']);
    Route::get('/student-portfolio/{studentUserId}', [StudentController::class, 'getStudentPortfolio']);

    Route::post('create-jobs', [CreateJobController::class, 'store']);
    Route::post('project/proposals', [ProposalController::class, 'store']);
    Route::get('fetch-job-lists', [CreateJobController::class, 'index']);
    Route::get('project/proposals/show/{userID}', [ProposalController::class, 'show']);
    Route::get('project/created/show/{userID}', [CreateJobController::class, 'show']);
    Route::post('update-jobs/{projectID}', [CreateJobController::class, 'update']);
});

Route::post('google-callback/auth/google-login', [RegisterController::class, 'googleCallback']);
Route::post('email-password/auth/login', [StudentController::class, 'loginEmailPassword']);
Route::post('email-password/auth/register', [StudentController::class, 'registerEmailPassword']);
