<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['completed', 'pending'];
        foreach($statuses as $status){
            Status::create([
                'name' => $status
            ]);
        }
    }
}
