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

    //get the list of election posts given the election id
    public function getElectionPositionsList($election_id)
    {
        $keyword = request()->input('keyword');
        $searchQuery = trim($keyword);
        
        if($searchQuery == ''){
            $autocomplate = DB::table('posts')
            ->select('posts.id','posts.name AS post_name')
            ->where('posts.election_id', '=', $election_id)         
            ->limit(10)       
            ->orderby('posts.name','asc')      
            ->get();

        }else{
            $autocomplate = DB::table('posts')
            ->select('posts.id','posts.name AS post_name')
            ->where([
                ['posts.name', 'like', '%' .$searchQuery . '%'],
                ['posts.election_id', '=', $election_id]
            ])        
            ->limit(10)
            ->orderby('posts.name','asc')     
            ->get();
      }
      
      $response = array();

     foreach($autocomplate as $autocomplate){

         $response[] = array("value"=>$autocomplate->id,"label"=>$autocomplate->post_name);

     }
        return response()->json($response);
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
            return response()->json(['success'=>'Elective position created successfully']);
        }else{
            return response()->json(['error'=>'Failed to create position']);
        } 
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
            'elections_dropdown' => 'required',
            'postName' => 'required|max:191',
            'postDescription' => 'required|max:255',
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('postName');
        $election_id = request()->input('elections_dropdown');
        $description = request()->input('postDescription');

        $updateRes = DB::table('posts')
            ->where('id', $postId)
            ->update(array('name' => $name, 'election_id' => $election_id,
            'updated_at' => now(),'description' => $description));

        if($updateRes){
            return response()->json(['success'=>'Post updated successfully']);
        }else{
            return response()->json(['error'=>'Failed to update post']);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $postId = request()->input('post_id');

        $deleteRes = DB::table('posts')
                            ->where([
                                ['id', '=', $postId]
                            ])
                            ->delete();
        if($deleteRes){
            return response()->json(['success'=>'Post deleted successfully']);
        }else{
            return response()->json(['error'=>'Failed to delete post']);
        }
    }
}
