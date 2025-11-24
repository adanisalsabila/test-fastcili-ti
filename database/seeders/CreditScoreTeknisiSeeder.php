<?php

namespace Database\Seeders;

use App\Models\CreditScoreTeknisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditScoreTeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreditScoreTeknisi::insert([
            ['id_user' => 5],
            ['id_user' => 6],
            ['id_user' => 8],
            ['id_user' => 9],
        ]);
        CreditScoreTeknisi::insert([
            [
                'id_user' => 7,
                'credit_score' => 90
            ],
        ]);
    }
}
