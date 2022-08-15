<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectionsController;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\PostsController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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

//manage users
//Route::get('/manage-users', [UsersController::class, 'index'])->name('manage_users');

//view election results
Route::get('/election-results', [VotesController::class, 'index'])->name('voting_results');

//route to create a new election
Route::post('/create-election', [ElectionsController::class, 'store'])->name('create_election');

//route to delete an election
Route::post('/delete-election', [ElectionsController::class, 'destroy'])->name('delete_election');

//route to create a new position
Route::post('/create-position', [PostsController::class, 'store'])->name('create_position');

//route to create a new candidate
Route::post('/create-candidate', [CandidatesController::class, 'store'])->name('create_candidate');

//route to edit a candidate
Route::post('/edit-candidate', [CandidatesController::class, 'update'])->name('edit_candidate');

//get elections list for table
Route::get('/elections/table-list', [ElectionsController::class, 'getAllElections'])->name('election_results');

//get candidates list for table
Route::get('/candidates/table-list', [CandidatesController::class, 'getAllCandidates']);

//get candidate details for edit
Route::get('/edit-candidate-details/{candidateId}', [CandidatesController::class, 'getCandidateDetails']);

//get election details for edit
Route::get('/edit-election-details/{electionId}', [ElectionsController::class, 'getElectionDetails']);

//save the edited election details
Route::post('/save-edit-election-details/{electionId}', [ElectionsController::class, 'update']);