<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            'company_id' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'account_information_id' => '1',
            'method_name'  => 'Bank',
            'status'  => '1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('payment_methods')->insert([
            'company_id' => '1',
            'created_by' => '1',
            'updated_by' => '1',
            'account_information_id' => '1',
            'method_name'  => 'Card',
            'status'  => '1',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}
