<?php

namespace Database\Seeders;


use App\AccountInformation;
use Illuminate\Database\Seeder;
use App\Company;
use App\User;

class dummy_account_information_table extends Seeder
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
            [
                'company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_name' => 'DBBL','account_no' => '123',  'branch_name' => 'Dhanmondi', 'status' =>  1],
            [
                'company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_name' => 'Brac','account_no' => '1234', 'branch_name' =>  'Uttara', 'status' =>  1],
            [
                'company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_name' => 'SIBL','account_no' => '12345', 'branch_name' =>  'Panthapath', 'status' =>  1],
            [
                'company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_name' => 'IBBL','account_no' => '123456', 'branch_name' =>  'Uttara', 'status' =>  1],
            [
                'company_id' => $company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_name' => 'Dhaka Bank','account_no' => '123654', 'branch_name' =>  'Panthapath', 'status' => 1],
        ];

        foreach ($data as $acc_info) {
            AccountInformation::create($acc_info);
        }
    }
}
