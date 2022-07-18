<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
