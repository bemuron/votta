<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $electionsList = $this->getCurrentElections();
        return view('home',compact('electionsList'));
    }

    //get the list of ongoing elections
    private function getCurrentElections(){
        if(Auth::user()){
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
                ['elections.status', '=', 1],
            ])
            ->orderBy('elections.end_date','asc')
            ->get();
        
    }
}
