<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_information')->insert([

            'company_id' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'account_name' => 'DBBL',
            'account_no'  => '201907141213',
            'branch_name'  => 'Dhaka',
            'status'  => '1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()

        ]);
        DB::table('account_information')->insert([

            'company_id' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'account_name' => 'Islami Bank',
            'account_no'  => '201907141595',
            'branch_name'  => 'Dhaka',
            'status'  => '1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()

        ]);
    }
}
