<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\ChartType;

class ChartTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chart_types = ['income', 'expense', 'asset', 'liability and equity'];

        foreach($chart_types as $chart_type){
            ChartType::create([
                'company_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'name' => $chart_type
            ]);
        }
    }
}
