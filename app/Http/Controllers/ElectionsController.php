<?php

namespace App\Http\Controllers;

use App\Models\Elections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ElectionResultsExport;
use App\Exports\CandidateResultsExport;

class ElectionsController extends Controller
{
    private $election_id = 0;
    /**
     * Display a listing of the elections.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(base_path('public/images/elections/'));
        $electionsList = $this->getCurrentElections();
        
        return view('manage_elections',compact('electionsList'));
    }

    //get the list of ongoing elections
    private function getCurrentElections(){
        
        return DB::table('elections')
            ->select('id',
                    'name',
                    DB::raw("IF(LENGTH(description) <= 40, description,
                            CONCAT(LEFT(description, 40), '...')) AS description"), 
                    'image')
            ->where([
                ['end_date', '>', NOW()],
                ['status', '=', 1]
            ]) 
            ->orderBy('end_date','asc')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add_election');
    }

    public function showCurrentElections()
    {
        $electionsList = $this->getCurElections();
        return view('ongoing_elections',compact('electionsList'));
    }

    //get the list of ongoing elections
    private function getCurElections(){

        if(auth()->check()){
            $user_id = auth()->user()->id;
            $sub_division = auth()->user()->sub_division;

            $division = DB::table('users')
                ->select('user_sub_divisions.division_id')
                ->join('user_sub_divisions', 'user_sub_divisions.id', '=', 'users.sub_division') 
                ->join('user_divisions', 'user_divisions.id', '=', 'user_sub_divisions.division_id')   
                ->where([
                    ['users.id', '=', $user_id]
                        ])
                ->first();

            return DB::table('elections')
                ->distinct()
                ->select('elections.id',
                        'elections.name','elections.end_date',
                        DB::raw("IF(LENGTH(elections.description) <= 40, elections.description,
                                CONCAT(LEFT(elections.description, 40), '...')) AS description"), 
                        'elections.image')
                ->join('voter_bases', 'voter_bases.election_id', '=', 'elections.id')
                ->where([
                    ['elections.end_date', '>', NOW()],
                    ['elections.status', '=', 1],
                    ['voter_bases.sub_division_id', '=', $sub_division]
                ])
                ->orWhere([
                    ['elections.end_date', '>', NOW()],
                    ['elections.status', '=', 1],
                    ['voter_bases.division_id', '=', $division->division_id]
                ])
                ->orderBy('elections.end_date','asc')
                ->get();
        }

        return DB::table('elections')
                ->distinct()
                ->select('elections.id',
                        'elections.name','elections.end_date',
                        DB::raw("IF(LENGTH(elections.description) <= 40, elections.description,
                                CONCAT(LEFT(elections.description, 40), '...')) AS description"), 
                        'elections.image')
                ->where([
                    ['elections.end_date', '>', NOW()],
                    ['elections.status', '=', 1]
                ])
                ->orderBy('elections.end_date','asc')
                ->get();

        
    }

    /**
     * Store a newly created resource in storage.
     * save a new election/poll details to the db
     * election statuses 0 - default/draft, 1 - ongoing/live, 2 - paused, 3 - completed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'electionName' => 'required|max:191',
            'electDateFrom' => 'required',
            'electDateTo' => 'required',
            'electionStatus' => 'required',
            'electionBigImg' => 'required|image|dimensions:width=1100,height=281',
            'electionThumbImg' => 'required|image|dimensions:width=288,height=288',
            'electionDescription' => 'required|max:255',
        ]);

        //dd(base_path('public/images/elections/'));
        if (request()->hasFile('electionBigImg')) {

            $thumbImgName = request()->file('electionThumbImg')->getClientOriginalName();
            $bigImgName = request()->file('electionBigImg')->getClientOriginalName();

            $bigImgFile = request()->file('electionBigImg');
            $smallImgFile = request()->file('electionThumbImg');

            //move the file to the right folder
            if($bigImgFile->move(base_path('public/images/elections/'), $bigImgFile->getClientOriginalName())){
                //move the file to the right folder
                if($smallImgFile->move(base_path('public/images/elections/'), $smallImgFile->getClientOriginalName())){
                    $user_id = auth()->user()->id;
                    $name = request()->input('electionName');
                    $desc = request()->input('electionDescription');
                    $to = request()->input('electDateTo');
                    $from = request()->input('electDateFrom');
                    $status = request()->input('electionStatus');

                    $insertRes = DB::table('elections')->insertGetId(array('name' => $name,'description' => $desc, 
                        'created_by' => $user_id, 'start_date' => $from, 'image_big' => $bigImgName, 
                        'image' => $thumbImgName, 'status' => $status, 
                        'end_date' => $to, 'created_at' => now()));
                    if($insertRes){
                        return response()->json(['success'=>'Election created successfully']);
                    }else{
                        return response()->json(['error'=>'Failed to create election']);
                    }    
                }else{
                    return response()->json(['error'=>'Failed to move file']);
                }
            }else{
                return response()->json(['error'=>'Failed to move file']);
            }
        }else{
            return response()->json(['error'=>'File not found']);
        }
        
    }

    //get the list of all elections
    public function getAllElections(){
        if (request()->ajax()) {
            return DB::table('elections')
            ->select('elections.id','elections.name',
                DB::raw("(CASE WHEN elections.status = 0 THEN 'Draft' "
                . "WHEN elections.status  = 1 THEN 'Live / Ongoing' "
                . "WHEN elections.status  = 2 THEN 'Paused' "
                . "WHEN elections.status  = 3 THEN 'Completed'END) AS status"),
                'elections.image','elections.image_big','elections.start_date',
                    'elections.end_date','elections.created_at','users.name AS created_by',
                DB::raw("IF(LENGTH(description) <= 40, elections.description,
                        CONCAT(LEFT(description, 40), '...')) AS description"))
            ->join('users', 'elections.created_by', '=', 'users.id')                
            ->orderBy('elections.created_at','desc')
            ->get();
        }
        
    }

    /**
     * Display the details 0f the selected election.
     *
     * $election_id 
     * @return view with election details
     */
    public function show()
    {
        $election_id = request()->input('election_id');
        $electionDetails = DB::table('elections')
            ->select('id',
                    'name',
                    'description', 
                    'end_date',
                    'image',
                    'image_big')
            ->where([
                ['id', '=', $election_id]
            ]) 
            ->first();

        $electionCandidates = $this->getElectionCandidtates($election_id);  

        //dd($electionDetails);
        //return redirect('/election-details')->with('electionCandidates','electionDetails');
        //return redirect()->route('election_details',
          //      ['electionCandidates'=>$electionCandidates, 'electionDetails'=>$electionDetails]); 
        return view('election_details',compact('electionCandidates','electionDetails'));
    }

    //get all the candidates in this election
    public function getElectionCandidtates($electionId){
        return DB::table('candidates')
                ->select('candidates.id','candidates.candidate_name as name','candidates.post_id',
                DB::raw("IF(LENGTH(candidates.description) <= 40, candidates.description,
                            CONCAT(LEFT(candidates.description, 40), '...')) AS description"),
                'posts.name AS post_name','candidates.image','candidates.election_id')
                ->join('posts', 'posts.id', '=', 'candidates.post_id')
                ->join('elections', 'elections.id', '=', 'candidates.election_id')
                ->where([
                    ['candidates.election_id', '=', $electionId]
                        ])
                ->get();
    }

    //get a single candidate's details
    public function getCandidtateDetails($candidateId, $electionId){
        return DB::table('candidates')
                ->select('candidates.id','candidates.candidate_name as name',
                'candidates.description','posts.name AS post_name',
                'candidates.image','candidates.election_id','candidates.post_id')
                ->join('posts', 'posts.id', '=', 'candidates.post_id')
                ->join('elections', 'elections.id', '=', 'candidates.election_id')
                ->where([
                    ['candidates.id', '=', $candidateId],
                    ['candidates.election_id', '=', $electionId]
                        ])
                ->first();
    }

    //get single elction details by id
    public function getElectionDetails($electionId){
        
        return DB::table('elections')
        ->select('elections.id','elections.name','elections.status',
                'elections.image','elections.image_big','elections.start_date',
                'elections.end_date','elections.created_at','created_by',
                'elections.description')
            ->where([
                ['id', '=', $electionId]
            ]) 
            ->first();
    }

    /**
     * update the election details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  id of the election to update  $electionId
     * @return \Illuminate\Http\Response
     */
    public function update($electionId)
    {
        $validated = request()->validate([
            'electionName' => 'required|max:191',
            'electDateFrom' => 'required',
            'electDateTo' => 'required',
            'electionStatus' => 'required',
            'electionBigImg' => 'image|dimensions:width=1100,height=281',
            'electionThumbImg' => 'image|dimensions:width=288,height=288',
            'electionDescription' => 'required|max:255',
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('electionName');
        $desc = request()->input('electionDescription');
        $to = request()->input('electDateTo');
        $from = request()->input('electDateFrom');
        $status = request()->input('electionStatus');

        if (request()->hasFile('electionThumbImg')) {
            $thumbImgName = request()->file('electionThumbImg')->getClientOriginalName();
            $smallImgFile = request()->file('electionThumbImg');

            //move the file to the right folder
            if($smallImgFile->move(base_path('public/images/elections/'), $smallImgFile->getClientOriginalName())){
                $updateRes = DB::table('elections')
                    ->where('id', $electionId)
                    ->update(array('name' => $name, 'description' => $desc,'created_by' => $user_id,
                    'updated_at' => now(),'start_date' => $from,'image' => $thumbImgName,
                    'end_date' => $to,'status' => $status));

                if($updateRes){
                    return response()->json(['success'=>'Election updated successfully']);
                }else{
                    return response()->json(['error'=>'Failed to update election']);
                }     
            }

        }

        if (request()->hasFile('electionBigImg')) {
            $bigImgName = request()->file('electionBigImg')->getClientOriginalName();
            $bigImgFile = request()->file('electionBigImg');

            //move the file to the right folder
            if($bigImgFile->move(base_path('public/images/elections/'), $bigImgFile->getClientOriginalName())){
                $updateRes = DB::table('elections')
                    ->where('id', $electionId)
                    ->update(array('name' => $name, 'description' => $desc,'created_by' => $user_id,
                    'updated_at' => now(),'start_date' => $from,'image_big' => $bigImgName,
                    'end_date' => $to,'status' => $status));

                if($updateRes){
                    return response()->json(['success'=>'Election updated successfully']);
                }else{
                    return response()->json(['error'=>'Failed to update election']);
                }      
            }

        }

        //if no image was added
        $updateRes = DB::table('elections')
            ->where('id', $electionId)
            ->update(array('name' => $name, 'description' => $desc,'created_by' => $user_id,
            'updated_at' => now(),'start_date' => $from,
            'end_date' => $to,'status' => $status));

        if($updateRes){
            return response()->json(['success'=>'Election updated successfully']);
        }else{
            return response()->json(['error'=>'Failed to update election']);
        }
    }

    /**
     * Remove the specified election from db.
     *
     * @param  id of the election to delete  $electionId
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $electionId = request()->input('election_id');

        return DB::table('elections')
                            ->where([
                                ['id', '=', $electionId]
                            ])
                            ->delete();
    }

    //export the election results table to excel
    public function exportElectionResults() {
        return (new ElectionResultsExport())->download(date('YmdHis').'.xlsx');
    }

    //export the election results table to excel
    public function exportCandidateResults($election_id) {
        $election = DB::table('elections')
            ->select('elections.name')
            ->where([
                ['id', '=', $election_id]
            ]) 
            ->first();
        return (new CandidateResultsExport($election_id))->download($election->name.'.xlsx');
    }
}
