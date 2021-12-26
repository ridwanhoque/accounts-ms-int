<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Customer;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::insert([
            'company_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'customer_code' => 'c-001',
            'name' => 'customer1',
            'phone' => '01711223344',
            'email' => 'customer@gmail.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
