<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\PettycashChart;
use App\Company;
use App\User;

class dummy_pettycash_charts_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_id = Company::first()->id;
        $user_id = User::first()->id;

        $data = [
            ['company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'name' => 'food', 'status' => 1],
        ];

    }
}
