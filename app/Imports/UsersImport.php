<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, SkipsEmptyRows, WithHeadingRow, 
WithValidation,SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsFailures;

    private $mUserId;

    public function __construct($userId)
    {
        $this->mUserId = $userId;
        
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $salt1 = sha1(rand());
        $salt = substr($salt1, 0, 10);

        return new User([
            //'id'     => $row[0],
            'name'    => $row['name'],
            'email' => $row['email'],
            'password' => bcrypt($row['password']),
            'user_role' => $row['user_role'],
            'sub_division' => $row['sub_division'],
            'created_at' => date('Y-m-d H:i:s'),
            'status' => $row['status'],
            'salt' => $salt,
        ]);
    }

    /**
    * rules
    * validation rules for each row being inserted into the database
    * 
    */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'status' => ['required','numeric'],
            'user_role' => ['required','numeric'],
            'sub_division' => ['required','numeric'],
            'email' => ['required'],
            //'created_at' => ['required'],
            'password' => ['required','string']
        ];
    }
    
    /**
    * WithBatchInserts
    * This batch size will determine how many models will be inserted into the 
    * database in one time. This will drastically reduce the import duration.
    * 
    */
    public function batchSize(): int
    {
        return 1000;
    }
    
    /**
    * WithChunkReading
    * This will read the spreadsheet in chunks and keep the memory usage under control.
    * 
    */
    public function chunkSize(): int
    {
        return 1000;
    }
}
