<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manage_candidates');
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
        $validated = $request->validate([
            'candidate_election_dropdown' => 'required',
            'candidate_position_dropdown' => 'required',
            'candidateImg' => 'required|image|dimensions:width=288,height=288',
            'candidateDescription' => 'required|max:3000',
            'candidateName' => 'required'
        ]);

        if (request()->hasFile('candidateImg')) {
            $imgName = request()->file('candidateImg')->getClientOriginalName();
            $imgFile = request()->file('candidateImg');

            //move the file to the right folder
            if($imgFile->move(base_path('public/images/candidates/'), $imgName)){
                $insertRes = DB::table('candidates')->insertGetId(array('description' => $validated['candidateDescription'],
                'candidate_name' => $validated['candidateName'], 'election_id' => $validated['candidate_election_dropdown'], 
                'post_id' => $validated['candidate_position_dropdown'], 'image' => $imgName, 
                'created_at' => date('Y-m-d H:i:s')));

                if($insertRes){
                    return redirect()->back()->with("success","Candidate created successfully");
                }else{
                    return redirect()->back()->with("error","Failed to create candidate");
                }
            }

        }else{
            return redirect()->back()->with("error","File not found");
        }

        
    }

    //get single candidate details by id
    public function getCandidateDetails($candidateId){
        
        return DB::table('candidates')
        ->select('candidates.id','posts.name AS post_name','candidates.post_id',
                'candidates.candidate_name',
                'candidates.image','elections.name AS election_name','candidates.election_id',
                'candidates.description','candidates.created_at')
        ->join('posts', 'posts.id', '=', 'candidates.post_id')
        ->join('elections', 'elections.id', '=', 'candidates.election_id')
            ->where([
                ['candidates.id', '=', $candidateId]
            ]) 
            ->first();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function show(Candidates $candidates)
    {
        //
    }

    /**
     * get a list of users so the user can select a candidate
     *
     */
    public function getCandidatesList()
    {
        $keyword = request()->input('keyword');
        $searchQuery = ucfirst(trim($keyword));
        
        if($searchQuery == ''){
            $autocomplate = DB::table('users')
            ->select('id','name')
            ->limit(10)
            ->orderby('name','asc')
            ->get();

        }else{
            $autocomplate = DB::table('users')
            ->select('id','name')
            ->where('name', 'like', '%' .$searchQuery . '%')
            ->limit(10)
            ->orderby('name','asc')
            ->get();
      }
      
      $response = array();

     foreach($autocomplate as $autocomplate){

         $response[] = array("value"=>$autocomplate->id,"label"=>$autocomplate->name);

     }
        return response()->json($response);
    }

    //get the list of all candidates
    //to show in the datatables
    public function getAllCandidates(){
        if (request()->ajax()) {
            return DB::table('candidates')
            ->select('candidates.id','posts.name AS post_name',
                'candidates.image','elections.name AS election_name',
                    'candidates.description','candidates.created_at','candidates.candidate_name')
            ->join('posts', 'posts.id', '=', 'candidates.post_id')
            ->join('elections', 'elections.id', '=', 'candidates.election_id')
            ->orderBy('candidates.created_at','desc')
            ->get();
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required',
            'position_id' => 'required',
            'record_id' => 'required',
            'editCandidateImg' => 'image|dimensions:width=288,height=288',
            'editCandidateDescription' => 'required|max:3000',
            'editCandidateName' => 'required|max:50'
        ]);

        if (request()->hasFile('editCandidateImg')) {
            $imgName = request()->file('editCandidateImg')->getClientOriginalName();
            $imgFile = request()->file('editCandidateImg');

            //move the file to the right folder
            if($imgFile->move(base_path('public/images/candidates/'), $imgName)){

                $updateRes = DB::table('candidates')
                ->where('id', $validated['record_id'])
                ->update(array('candidate_name' => $validated['editCandidateName'], 'election_id' => $validated['election_id'],
                'post_id' => $validated['position_id'], 'image' => $imgName,
                'updated_at' => date('Y-m-d H:i:s'),'description' => $validated['editCandidateDescription']));

                if($updateRes){
                    return response()->json(['success'=>'Candidate updated successfully.']);
                }else{
                    return response()->json(["error","Failed to update candidate"]);
                }
            }

        }else{
            $updateRes = DB::table('candidates')
                ->where('id', $validated['record_id'])
                ->update(array('user_id' => $validated['candidate_id'], 'election_id' => $validated['election_id'],
                'post_id' => $validated['position_id'],
                'updated_at' => date('Y-m-d H:i:s'),'description' => $validated['editCandidateDescription']));

                if($updateRes){
                    return response()->json(['success'=>'Candidate updated successfully.']);
                }else{
                    return response()->json(["error","Failed to update candidate"]);
                }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidates $candidates)
    {
        //
    }
}
