<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class StarterPlanSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $starterPlan = Plan::where('name', 'Starter')->first();

        if ($starterPlan) {
            $starterPlan->monthly_price = 9.90;
            $starterPlan->annual_price = 113;
            $starterPlan->has_ads = true;
            $starterPlan->can_comment = true;
            $starterPlan->lesson_access = 5;
            $starterPlan->has_chat_access = true;
            $starterPlan->boutique_discount = 5;
            $starterPlan->boutique_free_shipping = true;
            $starterPlan->has_cooking_space = false;
            $starterPlan->invitation_to_events = true;
            $starterPlan->renewal_discount = null;

            $starterPlan->save();
        }
    }
}

