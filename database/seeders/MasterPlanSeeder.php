<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class MasterPlanSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $masterPlan = new Plan();
        $masterPlan->name = 'Master';
        $masterPlan->monthly_price = 19;
        $masterPlan->annual_price = 220;
        $masterPlan->has_ads = false;
        $masterPlan->can_comment = true;
        $masterPlan->lesson_access = null; // Set lesson access to null for unlimited access
        $masterPlan->has_chat_access = true;
        $masterPlan->boutique_discount = 5;
        $masterPlan->boutique_free_shipping = true;
        $masterPlan->has_cooking_space = true;
        $masterPlan->invitation_to_events = true;
        $masterPlan->renewal_discount = 10;
        $masterPlan->save();
    }
}

