<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ElectionResultsExport implements FromQuery, WithHeadings
{
    use Exportable;

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return DB::table('candidates')
            ->select(
                    'elections.name AS election_name',
                    'elections.start_date','elections.end_date',
                    'candidates.candidate_name',
                    DB::raw("COALESCE( (select COUNT(voter_id) from votes where candidates.id = votes.candidate_id), 0 ) AS total_votes"))
            ->join('posts', 'posts.id', '=', 'candidates.post_id')
            ->join('votes', 'votes.candidate_id', '=', 'candidates.id')
            ->join('elections', 'elections.id', '=', 'candidates.election_id')
            //->where('elections.end_date', '<', date('Y-m-d'))   
            ->groupBy('candidates.election_id')
            ->orderBy('elections.end_date','asc');
    }

    public function headings(): array {
        return [
            'ELECTION NAME',
            'FROM',
            'TO',
            'WINNER',
            'VOTES RECEIVED'
        ];
    }
}
