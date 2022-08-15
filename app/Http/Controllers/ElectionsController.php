<?php

namespace App\Http\Controllers;

use App\Models\Elections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        
        return DB::table('elections')
            ->select('id',
                    'name','end_date',
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
                        return redirect()->back()->with("success","Election created successfully");
                    }else{
                        return redirect()->back()->with("error","Failed to create election");
                    }    
                }else{
                    return redirect()->back()->with("error","Failed to move file");
                }
            }else{
                return redirect()->back()->with("error","Failed to move file");
            }
        }else{
            return redirect()->back()->with("error","File not found");
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
            'editElectionName' => 'required|max:191',
            'editElectDateFrom' => 'required',
            'editElectDateTo' => 'required',
            'editElectionStatus' => 'required',
            'editElectionThumbImg' => 'image|dimensions:width=288,height=288',
            'editElectionBigImg' => 'image|dimensions:width=1100,height=281',
            'editElectionDescription' => 'required|max:255',
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('editElectionName');
        $desc = request()->input('editElectionDescription');
        $to = request()->input('editElectDateTo');
        $from = request()->input('editElectDateFrom');
        $status = request()->input('editElectionStatus');

        if (request()->hasFile('editElectionThumbImg')) {
            $thumbImgName = request()->file('editElectionThumbImg')->getClientOriginalName();
            $smallImgFile = request()->file('editElectionThumbImg');

            //move the file to the right folder
            if($smallImgFile->move(base_path('public/images/elections/'), $smallImgFile->getClientOriginalName())){
                $updateRes = DB::table('elections')
                    ->where('id', $electionId)
                    ->update(array('name' => $name, 'description' => $desc,'created_by' => $user_id,
                    'updated_at' => now(),'start_date' => $from,'image' => $thumbImgName,
                    'end_date' => $to,'status' => $status));

                if($updateRes){
                    return redirect()->back()->with("success","Election updated successfully");
                }else{
                    return redirect()->back()->with("error","Failed to update election");
                }     
            }


        }

        if (request()->hasFile('editElectionBigImg')) {
            $bigImgName = request()->file('editElectionBigImg')->getClientOriginalName();
            $bigImgFile = request()->file('editElectionBigImg');

            //move the file to the right folder
            if($bigImgFile->move(base_path('public/images/elections/'), $bigImgFile->getClientOriginalName())){
                $updateRes = DB::table('elections')
                    ->where('id', $electionId)
                    ->update(array('name' => $name, 'description' => $desc,'created_by' => $user_id,
                    'updated_at' => now(),'start_date' => $from,'image_big' => $bigImgName,
                    'end_date' => $to,'status' => $status));

                if($updateRes){
                    return redirect()->back()->with("success","Election updated successfully");
                }else{
                    return redirect()->back()->with("error","Failed to update election");
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
            return redirect()->back()->with("success","Election updated successfully");
        }else{
            return redirect()->back()->with("error","Failed to update election");
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
}
