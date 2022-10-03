<?php

namespace App\Http\Controllers;

use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotesController extends Controller
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

    //save a vote for a candidate
    public function voteCandidtate(){
        $voterId = auth()->user()->id;
        //$voterId = 1;
        $candidateId = request()->input('candidate_id');
        $electionId = request()->input('election_id');
        $postId = request()->input('post_id');

        //check if this user has already voted for someone in this election and position
        $alreadyVoted = $this->checkUserAlreadyVoted($electionId, $postId, $voterId);

        if($alreadyVoted->num_votes == 0){
            $insertResponse =  DB::insert('insert into votes (candidate_id, '
                . 'election_id, post_id, voter_id, created_at) values (?,?,?,?,?)',
                [$candidateId, $electionId,$postId,$voterId,now()]);

            if($insertResponse){
                return 1;
            }else{
                return 0;
            }
        }else{
            //user already voted
            $result = $this->getWhoUserVoted($electionId, $postId, $voterId);
            return $result;
        }
    }

    //check if user already voted
    public function checkUserAlreadyVoted($electionId, $postId, $voterId){
        return DB::table('votes')
            ->select(DB::raw('count(*) as num_votes'))
            ->where([
                ['votes.election_id', '=', $electionId],
                ['votes.post_id', '=', $postId],
                ['votes.voter_id', '=', $voterId]
                    ])    
            ->first();
    }

    //check which user voted for a particular position
    public function getWhoUserVoted($electionId, $postId, $voterId){
        return DB::table('votes')
                ->select('candidates.candidate_name AS name','posts.name AS post_name')
                ->join('candidates', 'candidates.id', '=', 'votes.candidate_id')
                ->join('posts', 'posts.id', '=', 'candidates.post_id')
                ->where([
                    ['posts.id', '=', $postId],
                    ['candidates.election_id', '=', $electionId],
                    ['votes.voter_id', '=', $voterId]
                        ])
                ->limit(1)        
                ->first();
    }

    //get all the elections
    public function getAllElections(){
        return DB::table('elections')
            ->select('id',
                    'name',
                    'end_date', 
                    'image')
            ->where([
                ['status', '=', 1]
                    ])
            ->orderBy('end_date','desc')
            ->get();
    }

    //get all posts
    public function getAllPosts(){
        
        return DB::table('posts')
        ->select('posts.id','posts.election_id','posts.name AS post_name','posts.description')
            ->get();
    }

    //get candidates and their results
    public function getAllCandidatesElectionResults(){
        $resArr = array();
        $highestVotes = 0;
        $isWinner = false;

        return DB::table('candidates')
        ->select('candidates.id','candidates.post_id',
                DB::raw("COALESCE( (select COUNT(voter_id) from votes where candidates.id = votes.candidate_id), 0 ) AS total_votes"),
                'candidates.image','candidates.election_id',
                'candidates.candidate_name')
        ->join('posts', 'posts.id', '=', 'candidates.post_id')
        ->join('elections', 'elections.id', '=', 'candidates.election_id')
        ->groupBy('candidates.id')
        ->get();

        // for ($r = 0; $r < count($results); $r++){
        //     if($results[$r]->total_votes > $highestVotes){
        //         $highestVotes = $results[$r]->total_votes;
        //     }
        //     array_push($resArr,$results[$r]->branch_code);
        // }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $electionsList = $this->getAllElections();
        $postsList = $this->getAllPosts();
        $electionResults = $this->getAllCandidatesElectionResults();
        //dd($electionResults);
        return view('voting_results',compact('electionsList','postsList','electionResults'));
    }

    public function getDashBoardElectionResults()
    {
        return view('election_results');
    }

    /**
     * get all the election results
     *
     */
    public function getElectionResults()
    {
        if (request()->ajax()) {
                
            return DB::table('candidates')
            ->select('candidates.election_id', 'votes.id','candidates.post_id',
                    DB::raw("COALESCE( (select COUNT(voter_id) from votes where candidates.id = votes.candidate_id), 0 ) AS total_votes"),
                    'elections.name AS election_name',
                    'candidates.candidate_name','elections.start_date','elections.end_date')
            ->join('posts', 'posts.id', '=', 'candidates.post_id')
            ->join('votes', 'votes.id', '=', 'candidates.id')
            ->join('elections', 'elections.id', '=', 'candidates.election_id')
            //->where('elections.end_date', '<', date('Y-m-d'))   
            ->groupBy('candidates.election_id')
            ->orderBy('elections.end_date','asc')
            ->get();

            //TODO add condition to pick elections whose end date has passed
        }
    }

    public function getElectionSummaryDetails($votesId, $electionId){
        logger("getting details");
        $resArr = array();

        $electionName = DB::table('elections')
        ->select('elections.name')
        ->where('elections.id', '=', $electionId)
        ->first();

        array_push($resArr, $electionName);

        $votesCast = DB::table('votes')
            ->select(DB::raw("COALESCE( (COUNT(voter_id)), 0 ) AS votes_cast"))
            ->where('votes.election_id', '=', $electionId)
            ->first();

            //return $votesCast->votes_cast;
        //logger($votesCast);

        array_push($resArr, $votesCast);

        $candidatesNum = DB::table('candidates')
            ->select(DB::raw("COALESCE( (COUNT(id)), 0 ) AS candidates_num"))
            ->where('candidates.election_id', '=', $electionId)
            ->first();

        array_push($resArr, $candidatesNum);

        $voterBase = $this->getElectionVoterBaseCount($electionId);
        array_push($resArr, $voterBase);

        $period = DB::table('elections')
        ->select('elections.start_date','elections.end_date')
        ->where('elections.id', '=', $electionId)
        ->first();

        array_push($resArr, $period->start_date);
        array_push($resArr, $period->end_date);

        return response()->json($resArr);

    }

    //get number of voters in an election
    private function getElectionVoterBaseCount($electionId){
        $num_users = 0;
        $voterBase = DB::table('voter_bases')
            ->select('voter_bases.division_id','voter_bases.sub_division_id')
            ->where('voter_bases.election_id', '=', $electionId)
            ->get();

        //loop through the results and get users in the divisions or sub divisions
        for ($r = 0; $r < count($voterBase); $r++){
            if($voterBase[$r]->sub_division_id > 0){
                $num_users = $num_users + $this->getUsersInSubDivision($voterBase[$r]->sub_division_id);
            }else{
                $num_users = $num_users + $this->getUsersInDivision($voterBase[$r]->division_id);
            }
        }

        return $num_users;
    }

    //get the number of users in a sub division
    private function getUsersInSubDivision($sub_div_id){
        $users = DB::table('users')
            ->select(DB::raw("COALESCE( (COUNT(id)), 0 ) AS num_users"))
            ->where('users.sub_division', '=', $sub_div_id)
            ->first();

        return $users->num_users;
    }

    //get the number of users in a division
    private function getUsersInDivision($div_id){
        $num_div_users = 0;

        //get sub divs in this division
        $subDivs = DB::table('user_sub_divisions')
            ->select('user_sub_divisions.id')
            ->where('user_sub_divisions.division_id', '=', $div_id)
            ->get();

        //loop through the results and get users in the divisions or sub divisions
        for ($r = 0; $r < count($subDivs); $r++){
            $num_div_users = $num_div_users + $this->getUsersInSubDivision($subDivs[$r]->id);
        }

        return $num_div_users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Votes  $votes
     * @return \Illuminate\Http\Response
     */
    public function show(Votes $votes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Votes  $votes
     * @return \Illuminate\Http\Response
     */
    public function edit(Votes $votes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Votes  $votes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Votes $votes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Votes  $votes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Votes $votes)
    {
        //
    }
}
