<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plans;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $plan = new Plans();
        $plan->name = 'Standard';
        $plan->price = '0.00';
        $plan->save();

        $plan = new Plans();
        $plan->name = 'Premium';
        $plan->price = '20.00';
        $plan->save();
    }
}
