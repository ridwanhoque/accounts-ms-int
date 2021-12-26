<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\OwnerType;

class OwnerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owner_types = ['party', 'bank', 'cash', 'chart_head'];

        foreach($owner_types as $owner_type){
            OwnerType::create([
                'name' => $owner_type
            ]);
        }

    }
}
