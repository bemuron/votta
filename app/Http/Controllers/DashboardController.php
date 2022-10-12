<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //user should be signed in
        $this->middleware('auth');
    }

    //get the admin dashboard
    public function getDashboardView()
    {
        $allElections = $this->getAllElections();
        $ongoingPolls = $this->getOngoingElections();
        $completedPolls = $this->getCompletedElections();
        $votesCast = $this->getTotalVotesCast();
        $completedPollsDets = $this->getCompletedElectionsDetails();
        $startedElections = $this->getStartedElections();
        $createdcandidates = $this->getCreatedCandidates();
        $usersAdded = $this->getUsersAdded();
        return view('statistics',compact('allElections', 'ongoingPolls', 
        'completedPolls', 'votesCast', 'completedPollsDets','startedElections',
        'createdcandidates','usersAdded'));
    }

    //get number of all the elctions 
    public function getAllElections() {

        return DB::select( DB::raw("SELECT count(*) as allelections
            from elections"));
    }

    //get the number of ongoing elections
    private function getOngoingElections(){

        return DB::table('elections')
                ->select(DB::raw('count(*) as ongoingelects'))
                ->where('elections.status', '=', 1)
                ->first();
    }

    //get the number of ongoing elections
    private function getCompletedElections(){

        return DB::table('elections')
                ->select(DB::raw('count(*) as finishedelects'))
                ->where('elections.status', '=', 3)
                ->first();
    }

    //get the number of all votes cast for all elections
    private function getTotalVotesCast(){

        return DB::table('votes')
                ->select(DB::raw('count(*) as numvotes'))
                ->first();
    }

    //get the completed elections
    private function getCompletedElectionsDetails(){

        return DB::table('elections')
                ->select('elections.name', 'elections.end_date',
                DB::raw("(select count(*) from candidates c where elections.id = c.election_id) as numcandidates"))
                ->where('elections.status', '=', 3)
                ->limit(10)
                ->get();
    }

    //get the started elections
    private function getStartedElections(){
        return DB::table('elections')
            ->distinct()
            ->select('elections.id','elections.start_date',
                    'elections.name','elections.end_date',DB::raw("(CASE WHEN elections.status = 0 THEN 'Draft' "
                    . "WHEN elections.status  = 1 THEN 'Live / Ongoing' "
                    . "WHEN elections.status  = 2 THEN 'Paused' "
                    . "WHEN elections.status  = 3 THEN 'Completed'END) AS status"),
                    DB::raw("(select count(*) from candidates c where elections.id = c.election_id) as numcandidates"))
            ->where([
                ['elections.start_date', '<', NOW()]
            ])
            ->limit(5)
            ->orderBy('elections.end_date','asc')
            ->get();
    }

    //get created candidates
    private function getCreatedCandidates()
    {
        return DB::table('candidates')
            ->select('candidates.id','posts.name AS post_name',
                'candidates.image','elections.name AS election_name',
                DB::raw("(CASE WHEN elections.status = 0 THEN 'Draft' "
                    . "WHEN elections.status  = 1 THEN 'Live / Ongoing' "
                    . "WHEN elections.status  = 2 THEN 'Paused' "
                    . "WHEN elections.status  = 3 THEN 'Completed'END) AS election_status"),
                    'candidates.description','candidates.created_at','candidates.candidate_name')
            ->join('posts', 'posts.id', '=', 'candidates.post_id')
            ->join('elections', 'elections.id', '=', 'candidates.election_id')
            ->orderBy('candidates.created_at','desc')
            ->limit(5)  
            ->get();
    }

    //get users created
    private function getUsersAdded(){
        return DB::table('users')
        ->select('users.id','users.name','users.created_at',
            DB::raw("(CASE WHEN users.user_role = 1 THEN 'Administrator' "
                        . "WHEN users.user_role  = 0 THEN 'Default' "
                        . "ELSE 'Unknown' END) AS user_role"),
            DB::raw("(CASE WHEN users.status = 1 THEN 'Active' "
                        . "ELSE 'Deactivated' END) AS user_status"),
            'users.status','users.sub_division','user_sub_divisions.division_id',
            'user_sub_divisions.sub_division_name','user_divisions.division_name')
        ->join('user_sub_divisions', 'user_sub_divisions.id', '=', 'users.sub_division') 
        ->join('user_divisions', 'user_divisions.id', '=', 'user_sub_divisions.division_id')
        ->limit(5)           
        ->orderBy('users.created_at','desc')
        ->get();
    }
}
