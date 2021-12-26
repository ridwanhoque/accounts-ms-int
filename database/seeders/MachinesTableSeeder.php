<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Machine;

class MachinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $machinees = ['IF-01', 'IF-02', 'IF-03', 'CF-01', 'CF-02', 'CF-03'];

        foreach($machinees as $machine){
            Machine::create([
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'name' => $machine,
                'model' => $machine
            ]);    
        }
    }
}
