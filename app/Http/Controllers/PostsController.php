<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
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
        return view('manage_positions');
    }

    //get the list of elections for the dropdown
    public function getElectionsList()
    {
        $response = array();
        
        $types = DB::table('elections')
            ->select('id','name')
            ->get();
        
        foreach($types as $type){

         $response[] = array("id"=>$type->id,"name"=>$type->name);
     }
        return response()->json($response);
    }

    //get the list of all posts
    public function getAllPosts(){
        if (request()->ajax()) {
            return DB::table('posts')
            ->select('posts.id','posts.name AS post_name','posts.description',
                'elections.name AS election_name','elections.id AS election_id')
            ->join('elections', 'elections.id', '=', 'posts.election_id')                
            ->orderBy('posts.created_at','desc')
            ->get();
        }
        
    }

    //get single post details by id
    public function getPostDetails($postId){
        
        return DB::table('posts')
        ->select('posts.id','posts.name AS post_name','posts.description',
        'elections.name AS election_name','elections.id AS election_id')
        ->join('elections', 'elections.id', '=', 'posts.election_id')
            ->where([
                ['posts.id', '=', $postId]
            ]) 
            ->first();
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
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show(Posts $posts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function edit(Posts $posts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function update($postId)
    {
        $validated = request()->validate([
            'editPostName' => 'required|max:191',
            'edit_elections_dropdown' => 'required',
            'editPostDescription' => 'required|max:255',
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('editPostName');
        $election_id = request()->input('edit_elections_dropdown');
        $description = request()->input('editPostDescription');

        $updateRes = DB::table('posts')
            ->where('id', $postId)
            ->update(array('name' => $name, 'election_id' => $election_id,
            'updated_at' => now(),'description' => $description));

        if($updateRes){
            return redirect()->back()->with("success","Post updated successfully");
        }else{
            return redirect()->back()->with("error","Failed to update post");
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $posts)
    {
        //
    }
}
