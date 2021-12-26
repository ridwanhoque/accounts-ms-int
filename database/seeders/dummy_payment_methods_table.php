<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\AccountInformation;
use Illuminate\Support\Facades\Auth;

class dummy_payment_methods_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_information = AccountInformation::where('company_id', 1)->get();
        $user_id = Auth::user()->id;
        foreach($account_information as $acc_info){
            $data = [
                [
                    'company_id' => $acc_info->company_id, 'created_by' => $user_id, 'updated_by' => $user_id, 'account_information_id' => $acc_info->id,
                    'method_name' => $acc_info->name, 'status' => 1,
                ]
            ];    
        }

        dd($data);
    }
}
