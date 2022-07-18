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
            'elections_dropdown' => 'required',
            'postName' => 'required|max:191',
            'postDescription' => 'required|max:255',
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('postName');
        $desc = request()->input('postDescription');
        $election_id = request()->input('elections_dropdown');

        $insertRes = DB::table('posts')->insertGetId(array('name' => $name,'description' => $desc, 
            'election_id' => $election_id,'created_at' => now()));
        if($insertRes){
            return redirect()->back()->with("success","Elective position created successfully");
        }else{
            return redirect()->back()->with("error","Failed to create position");
        }
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

         $response[] = array("value"=>$autocomplate->id,"label"=>$autocomplate->category_name);

     }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidates $candidates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidates  $candidates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidates $candidates)
    {
        //
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
