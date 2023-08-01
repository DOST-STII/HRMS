<?php

namespace App\Imports;

use App;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class SalaryImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new App\SalaryTable([
           'salary_grade'=> $row[0],
           'salary_1'    => $row[1],
           'salary_2'    => $row[2],
           'salary_3'    => $row[3],
           'salary_4'    => $row[4],
           'salary_5'    => $row[5],
           'salary_6'    => $row[6],
           'salary_7'    => $row[7],
           'salary_8'    => $row[8],
        ]);
    }
}