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

    //check who user voted for a particular position
    public function getWhoUserVoted($electionId, $postId, $voterId){
        return DB::table('votes')
                ->select('users.name','posts.name AS post_name')
                ->join('candidates', 'candidates.id', '=', 'votes.candidate_id')
                ->join('users', 'candidates.user_id', '=', 'users.id')
                ->join('posts', 'posts.id', '=', 'candidates.post_id')
                ->where([
                    ['posts.id', '=', $postId],
                    ['candidates.election_id', '=', $electionId],
                    ['votes.voter_id', '=', $voterId]
                        ])
                ->limit(1)        
                ->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
