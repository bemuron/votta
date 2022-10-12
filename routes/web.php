<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectionsController;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserDivisionController;
use App\Http\Controllers\UserSubDivisionController;
use App\Http\Controllers\VoterBaseController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index'])->name('home');

//admin dashboard
Route::get('/dashboard', [DashboardController::class, 'getDashboardView'])->name('statistics');

//go to a single elections details
Route::get('/election-details', [ElectionsController::class, 'show'])->name('election_details');

//get the candidate details
Route::get('/candidate-details/{candidateId}/{electionId}', [ElectionsController::class, 'getCandidtateDetails']);

//cast a vote for a candidate
Route::post('/vote-candidate', [VotesController::class, 'voteCandidtate']);

//all current elections
Route::get('/ongoing-elections', [ElectionsController::class, 'showCurrentElections'])->name('ongoing_elections');

//admin pages
//manage elections
Route::get('/manage-elections', [ElectionsController::class, 'index'])->name('manage_elections');

//manage positions
Route::get('/manage-positions', [PostsController::class, 'index'])->name('manage_positions');

//get elections for dropdown
Route::get('/elections-dropdown', [PostsController::class, 'getElectionsList']);

//get candidates list
Route::get('/election-candidates-list', [CandidatesController::class, 'getCandidatesList']);

//route to delete a candidate
Route::post('/delete-candidate', [CandidatesController::class, 'destroy'])->name('delete_candidate');

//get posts list for table
Route::get('/posts/table-list', [PostsController::class, 'getAllPosts']);

//get election positions for dropdown
Route::get('/positions-dropdown/{election_id}', [PostsController::class, 'getElectionPositionsList']);

//get post details for edit
Route::get('/edit-post-details/{postId}', [PostsController::class, 'getPostDetails']);

//save the edited post details
Route::post('/save-edit-post-details/{postId}', [PostsController::class, 'update']);

//manage candidates
Route::get('/manage-candidates', [CandidatesController::class, 'index'])->name('manage_candidates');

//manage voter base
Route::get('/manage-voter-base', [VoterBaseController::class, 'index'])->name('manage_voter_base');

//manage users
Route::get('/manage-users', [UsersController::class, 'index'])->name('manage_users');

//manage users
Route::get('/manage-divisions', [UserDivisionController::class, 'index'])->name('manage_divisions');

//manage sub divisions
Route::get('/manage-sub-divisions', [UserSubDivisionController::class, 'index'])->name('manage_sub_divisions');

//get user details for edit
Route::get('/edit-user-details/{userId}', [UsersController::class, 'getSingleUser']);

//change the user status in the system
Route::post('/change-user-status', [UsersController::class, 'changeUserStatus']);

//get users list for table
Route::get('/users/table-list', [UsersController::class, 'getAllUsers']);

//upload users to db
Route::post('/users/import', [UsersController::class, 'importUsers']);

//donwload users upload template file
Route::get('/user-upload-template', [UsersController::class, 'downloadUserUploadTemplate']);

//route to delete a user
Route::post('/delete-user', [UsersController::class, 'destroy'])->name('delete_user');

//route to create a new user
Route::post('/create-user', [UsersController::class, 'store'])->name('create_user');

//save the edited user details
Route::post('/save-edit-user-details/{postId}', [UsersController::class, 'update']);

//get divisions for dropdown
Route::get('/divisions-dropdown', [UserDivisionController::class, 'getDivisionsList']);

//get list of sub divisions
Route::get('/sub-division/table-list', [UserSubDivisionController::class, 'getAllSubDivisions']);

//upload sub divisions to db
Route::post('/sub-division/import', [UserSubDivisionController::class, 'importSubDivisions']);

//download sub divisions upload template file
Route::get('/sub-div-upload-template', [UserSubDivisionController::class, 'downloadSubDivUploadTemplate']);

//get sub division details for edit
Route::get('/edit-sub-div-details/{subDivId}', [UserSubDivisionController::class, 'getSubDivDetails']);

//save the edited sub division details
Route::post('/save-edit-sub-div-details/{subDivId}', [UserSubDivisionController::class, 'update']);

//route to create a new sub division
Route::post('/create-sub-division', [UserSubDivisionController::class, 'store']);

//route to delete a sub division
Route::post('/delete-sub-division', [UserSubDivisionController::class, 'destroy']);

//get sub divisions for dropdown
Route::get('/sub-division-dropdown/{division_id}', [UserSubDivisionController::class, 'getSubDivisionsList']);

//save the edited division details
Route::post('/save-edit-div-details/{divId}', [UserDivisionController::class, 'update']);

//route to create a new division
Route::post('/create-division', [UserDivisionController::class, 'store']);

//get list of divisions
Route::get('/division/table-list', [UserDivisionController::class, 'getAllDivisions']);

//get division details for edit
Route::get('/edit-div-details/{divId}', [UserDivisionController::class, 'getDivDetails']);

//route to delete a division
Route::post('/delete-division', [UserDivisionController::class, 'destroy']);

//upload divisions to db
Route::post('/division/import', [UserDivisionController::class, 'importDivisions']);

//download divisions upload template file
Route::get('/div-upload-template', [UserDivisionController::class, 'downloadDivUploadTemplate']);

//route to add voters to an election
Route::post('/add-voters', [VoterBaseController::class, 'store']);

//get voters for an election
Route::get('/get-election-voters/{electionId}', [VoterBaseController::class, 'getElectionVoters']);

//route to delete a voter from an election
Route::post('/delete-election-voter', [VoterBaseController::class, 'destroy']);

//get single voter base
Route::get('/get-voters-details/{votersId}', [VoterBaseController::class, 'getVoterDetails']);

//view election results
Route::get('/election-results', [VotesController::class, 'index'])->name('voting_results');

// get the view for election results in the dashbord
Route::get('/dash-election-results', [VotesController::class, 'getDashBoardElectionResults'])->name('election_results');

//view all election results in dashboard datatable
Route::get('/get-election-results', [VotesController::class, 'getElectionResults']);

//get election summary results
Route::get('/election-summary-details/{votesId}/{elelctionId}', [VotesController::class, 'getElectionSummaryDetails']);

//route to create a new election
Route::post('/create-election', [ElectionsController::class, 'store'])->name('create_election');

//route to delete an election
Route::post('/delete-election', [ElectionsController::class, 'destroy'])->name('delete_election');

//route to create a new position
Route::post('/create-position', [PostsController::class, 'store'])->name('create_position');

//route to delete a post
Route::post('/delete-post', [PostsController::class, 'destroy'])->name('delete_post');

//route to create a new candidate
Route::post('/create-candidate', [CandidatesController::class, 'store'])->name('create_candidate');

//route to edit a candidate
Route::post('/edit-candidate', [CandidatesController::class, 'update'])->name('edit_candidate');

//get elections list for table
Route::get('/elections/table-list', [ElectionsController::class, 'getAllElections']);

//get candidates list for table
Route::get('/candidates/table-list', [CandidatesController::class, 'getAllCandidates']);

//get candidate details for edit
Route::get('/edit-candidate-details/{candidateId}', [CandidatesController::class, 'getCandidateDetails']);

//get election details for edit
Route::get('/edit-election-details/{electionId}', [ElectionsController::class, 'getElectionDetails']);

//save the edited election details
Route::post('/save-edit-election-details/{electionId}', [ElectionsController::class, 'update']);