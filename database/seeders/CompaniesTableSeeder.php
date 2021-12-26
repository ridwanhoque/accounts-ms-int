<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'ABC Company',
            'phone' => '01711223344',
            'email' => 'abc@company.com',
            'website' => 'abc.com',
            'address' => 'Dhaka',
            'company_logo' => 'default.jpg'
        ]);        
    }
}
