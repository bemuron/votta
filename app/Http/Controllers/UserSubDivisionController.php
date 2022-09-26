<?php

namespace App\Http\Controllers;

use App\Imports\SubDivisionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSubDivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('manage_sub_divisions');
    }

    //get the list of sub divisions for the dropdown
    // public function getSubDivisionsList()
    // {
    //     $response = array();
        
    //     $types = DB::table('user_sub_divisions')
    //         ->select('id','sub_division_name','division_id')
    //         ->get();
        
    //     foreach($types as $type){

    //      $response[] = array("id"=>$type->id,"sub_division"=>$type->sub_division_name);
    //  }
    //     return response()->json($response);
    // }

    //get the list of sub divisions given the division id
    public function getSubDivisionsList($division_id)
    {
        $keyword = request()->input('keyword');
        $searchQuery = trim($keyword);
        
        if($searchQuery == ''){
            $autocomplate = DB::table('user_sub_divisions')
            ->select('user_sub_divisions.id','user_sub_divisions.sub_division_name')
            ->where('user_sub_divisions.division_id', '=', $division_id)         
            ->limit(10)       
            ->orderby('user_sub_divisions.sub_division_name','asc')      
            ->get();

        }else{
            $autocomplate = DB::table('user_sub_divisions')
            ->select('user_sub_divisions.id','user_sub_divisions.sub_division_name')
            ->where([
                ['user_sub_divisions.sub_division_name', 'like', '%' .$searchQuery . '%'],
                ['user_sub_divisions.division_id', '=', $division_id]
            ])        
            ->limit(10)
            ->orderby('user_sub_divisions.sub_division_name','asc')     
            ->get();
      }
      
      $response = array();

     foreach($autocomplate as $autocomplate){

         $response[] = array("value"=>$autocomplate->id,"label"=>$autocomplate->sub_division_name);

     }
        return response()->json($response);
    }

    //get the list of all sub divs
    public function getAllSubDivisions(){
        if (request()->ajax()) {
            return DB::table('user_sub_divisions')
            ->select('user_sub_divisions.id','user_sub_divisions.sub_division_name',
                'user_divisions.division_name','user_sub_divisions.division_id',
                'user_sub_divisions.created_at')
            ->join('user_divisions', 'user_divisions.id', '=', 'user_sub_divisions.division_id')                
            ->orderBy('user_sub_divisions.created_at','desc')
            ->get();
        }
        
    }

    //get single sub division details by id
    public function getSubDivDetails($subDivId){
        
        return DB::table('user_sub_divisions')
        ->select('user_sub_divisions.id','user_sub_divisions.sub_division_name',
                'user_divisions.division_name','user_sub_divisions.division_id',
                'user_sub_divisions.created_at')
            ->join('user_divisions', 'user_divisions.id', '=', 'user_sub_divisions.division_id') 
            ->where([
                ['user_sub_divisions.id', '=', $subDivId]
            ]) 
            ->first();
    }

    //save edit to a sub division
    public function update($subDivId)
    {
        $validated = request()->validate([
            'divisions_dropdown' => 'required',
            'subDivName' => 'required|max:191'
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('subDivName');
        $division_id = request()->input('divisions_dropdown');

        $updateRes = DB::table('user_sub_divisions')
            ->where('id', $subDivId)
            ->update(array('sub_division_name' => $name, 'division_id' => $division_id,
            'updated_at' => now()));

        if($updateRes){
            return response()->json(['success'=>'Sub division updated successfully']);
        }else{
            return response()->json(['error'=>'Failed to update sub division']);
        } 
    }

    //create a new sub division
    public function store(Request $request)
    {
        $validated = $request->validate([
            'divisions_dropdown' => 'required',
            'subDivName' => 'required|max:191'
        ]);

        $user_id = auth()->user()->id;
        $name = request()->input('subDivName');
        $division_id = request()->input('divisions_dropdown');

        $insertRes = DB::table('user_sub_divisions')->insertGetId(array('sub_division_name' => $name, 
        'division_id' => $division_id,'created_at' => now()));
        if($insertRes){
            return response()->json(['success'=>'Sub division created successfully']);
        }else{
            return response()->json(['error'=>'Failed to create sub division']);
        } 
    }

    //delete a sub division
    public function destroy()
    {
        $subDivId = request()->input('subDivId');

        //check if sub division has users in it
        $userCount = DB::table('users')
                ->select('users.id')
                ->where([
                    ['users.sub_division', '=', $subDivId]
                    ])
                ->limit(1)
                ->get();

        if(empty($userCount)){
            return response()->json(['info'=>'Sub division not deleted because there are users in it']);
        }

        $deleteRes = DB::table('user_sub_divisions')
                            ->where([
                                ['id', '=', $subDivId]
                            ])
                            ->delete();
        if($deleteRes){
            return response()->json(['success'=>'Sub division deleted successfully']);
        }else{
            return response()->json(['error'=>'Failed to delete Sub division']);
        }
    }

    //import sub divisions to db
    public function importSubDivisions() {
        $user_id = auth()->user()->id;

        $validatedFile = request()->validate([
           'sub_divs_file' => 'required|mimes:xls,xlsx,txt,csv',
       ]);
        
        $fileName = request()->file('sub_divs_file')->getClientOriginalName();
        
        $subDivImport = new SubDivisionsImport($user_id );
        $subDivImport->import(request()->file('sub_divs_file'));

        // foreach ($subDivImport->failures() as $failure) {
        //     $failure->row(); // row that went wrong
        //     $failure->attribute(); // either heading key (if using heading row concern) or column index
        //     $failure->errors(); // Actual error messages from Laravel validator
        //     $failure->values(); // The values of the row that has failed.
        // }

        if(count($subDivImport->failures()) > 0){
            return response()->json(['error'=>$subDivImport->failures()]);
        }

        return response()->json(['success'=>'Sub Divisions imported successfully']);

    }

    //download the template for bulk sub division upload
    public function downloadSubDivUploadTemplate(){

        // $headers = [
        //     'Content-Type' => 'application/pdf',
        //  ];

        $file_path = base_path('public/uploads/sub_divisions_upload_template.csv');

        return response()->download($file_path, 'sub_divisions_upload_template.csv');
    }
}
