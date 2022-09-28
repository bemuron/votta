<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage_voter_base');
    }

    //add voter base to an electoion
    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required',
            'division_id' => 'required',
            'sub_div_id' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $electionId = $validated['election_id'];
        $divId = $validated['division_id'];
        $subDivId = $validated['sub_div_id'];

        if($subDivId == 0){
            $result = DB::table('voter_bases')
                ->select('voter_bases.id')
                ->where([
                    ['voter_bases.election_id', '=', $electionId],
                    ['voter_bases.division_id', '=', $divId]
                    ])
                ->get();

            if(count($result) >= 1){
                return response()->json(['info'=>'You already added this voter base']);
            }
        }

        if($subDivId > 0){
            $result = DB::table('voter_bases')
                ->select('voter_bases.id')
                ->where([
                    ['voter_bases.election_id', '=', $electionId],
                    ['voter_bases.division_id', '=', $divId],
                    ['voter_bases.sub_division_id', '=', $subDivId]
                    ])
                ->get();

            if(count($result) >= 1){
                return response()->json(['info'=>'You already added this voter base']);
            }
        }
        

        $insertRes = DB::table('voter_bases')->insertGetId(array('election_id' => $electionId,
        'division_id' => $divId, 
            'sub_division_id' => $subDivId));
        if($insertRes){
            return response()->json(['success'=>'Voters added successfully']);
        }else{
            return response()->json(['error'=>'Failed to add voters']);
        }
    }

    //get the voters of a specified election
    public function getElectionVoters($electionId)
    {
        $response = array();
        
        $voter_dets = DB::table('voter_bases')
            ->select('voter_bases.id',
                    'voter_bases.election_id','elections.name AS election_name',
                    'voter_bases.division_id','user_divisions.division_name',
                    DB::raw("(CASE WHEN voter_bases.sub_division_id = 0 THEN 'All Sub Divisions' "
                    . "ELSE ". (DB::raw("(SELECT s.sub_division_name from user_sub_divisions s WHERE s.id = voter_bases.sub_division_id)")) 
                    ."END) AS sub_division_name"))
            ->join('user_divisions', 'user_divisions.id', '=', 'voter_bases.division_id') 
            ->join('elections', 'elections.id', '=', 'voter_bases.election_id')  
            ->where('voter_bases.election_id', $electionId)   
            ->get();

        return response()->json($voter_dets);
    }

    //remove a voter from an election
    public function destroy()
    {
        $voterId = request()->input('voter_id');

        $deleteRes = DB::table('voter_bases')
                            ->where([
                                ['id', '=', $voterId]
                            ])
                            ->delete();
        if($deleteRes){
            return response()->json(['success'=>'Voters removed successfully']);
        }else{
            return response()->json(['error'=>'Failed to remove voters']);
        }
    }

    //get single voter base details by id
    public function getVoterDetails($voterId){

        return DB::table('voter_bases')
            ->select('voter_bases.id',
                    'voter_bases.election_id','elections.name AS election_name',
                    'voter_bases.division_id','user_divisions.division_name',
                    DB::raw("(CASE WHEN voter_bases.sub_division_id = 0 THEN 'All Sub Divisions' "
                    . "ELSE ". (DB::raw("(SELECT s.sub_division_name from user_sub_divisions s WHERE s.id = voter_bases.sub_division_id)")) 
                    ."END) AS sub_division_name"),'voter_bases.sub_division_id')
            ->join('user_divisions', 'user_divisions.id', '=', 'voter_bases.division_id') 
            ->join('elections', 'elections.id', '=', 'voter_bases.election_id')  
            ->where('voter_bases.id', $voterId)   
            ->first();
    }
}
