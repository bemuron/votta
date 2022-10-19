<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class CandidateResultsExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $election_id;
    
    public function __construct(string $election_id)
    {
        $this->election_id = $election_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return DB::table('candidates')
            ->select('candidates.candidate_name',
                    DB::raw("COALESCE( (select COUNT(voter_id) from votes where candidates.id = votes.candidate_id), 0 ) AS total_votes"))
            ->join('elections', 'elections.id', '=', 'candidates.election_id')
            ->where('elections.id', '=', $this->election_id)
            ->orderBy('total_votes','desc');
    }

    public function headings(): array {
        return [
            'CANDIDATE NAME',
            'VOTES RECEIVED'
        ];
    }
}
