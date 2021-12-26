<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Supplier;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'company_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'supplier_code' => 's-01',
            'name' => 'supplier 1',
            'email' => 'supplier@gmail.com',
            'phone' => '01711223344'
        ]);
    }
}
