<?php

namespace App\Http\Controllers;

use App\Models\UserDivision;
use App\Http\Requests\StoreUserDivisionRequest;
use App\Http\Requests\UpdateUserDivisionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserDivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage_divisions');
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
     * @param  \App\Http\Requests\StoreUserDivisionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validated = request()->validate([
            'divName' => 'required|max:191'
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('divName');

        $insertRes = DB::table('user_divisions')->insertGetId(array('division_name' => $name, 
        'created_at' => now()));
        if($insertRes){
            return response()->json(['success'=>'Division created successfully']);
        }else{
            return response()->json(['error'=>'Failed to create division']);
        } 
    }

    //get the list of all divisions
    public function getAllDivisions(){
        if (request()->ajax()) {
            return DB::table('user_divisions')
            ->select('user_divisions.id','user_divisions.division_name',
                'user_divisions.created_at')             
            ->orderBy('user_divisions.created_at','desc')
            ->get();
        }
        
    }

    //get single division details by id
    public function getDivDetails($divId){
        
        return DB::table('user_divisions')
        ->select('user_divisions.id','user_divisions.division_name',
                'user_divisions.created_at')
            ->where([
                ['user_divisions.id', '=', $divId]
            ]) 
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDivision  $userDivision
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDivision $userDivision)
    {
        //
    }

    //get the list of divisions for the dropdown
    public function getDivisionsList()
    {
        $response = array();
        
        $types = DB::table('user_divisions')
            ->select('id','division_name')
            ->get();
        
        foreach($types as $type){

         $response[] = array("id"=>$type->id,"division"=>$type->division_name);
     }
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserDivisionRequest  $request
     * @param  \App\Models\UserDivision  $userDivision
     * @return \Illuminate\Http\Response
     */
    //save edit to a division
    public function update($divId)
    {
        $validated = request()->validate([
            'divName' => 'required|max:191'
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('divName');

        $updateRes = DB::table('user_divisions')
            ->where('id', $divId)
            ->update(array('division_name' => $name,'updated_at' => now()));

        if($updateRes){
            return response()->json(['success'=>'Division updated successfully']);
        }else{
            return response()->json(['error'=>'Failed to update division']);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDivision  $userDivision
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $divId = request()->input('divId');

        //check if division has sub divisions in it
        $result = DB::table('user_sub_divisions')
                ->select('user_sub_divisions.id')
                ->where([
                    ['user_sub_divisions.division_id', '=', $divId]
                    ])
                ->limit(1)
                ->get();

        logger('here');
        logger($result);

        if(empty($result)){
            logger('inside if');
            return response()->json(['info'=>'Division not deleted because it has sub divisions']);
        }

        $deleteRes = DB::table('user_divisions')
                            ->where([
                                ['id', '=', $divId]
                            ])
                            ->delete();
        if($deleteRes){
            return response()->json(['success'=>'Division deleted successfully']);
        }else{
            return response()->json(['error'=>'Failed to delete division']);
        }
    }
}
