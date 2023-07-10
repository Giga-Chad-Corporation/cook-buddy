<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class FreePlanSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $freePlan = new Plan();
        $freePlan->name = 'Free';
        $freePlan->monthly_price = null;
        $freePlan->annual_price = null;
        $freePlan->has_ads = true;
        $freePlan->can_comment = true;
        $freePlan->lesson_access = 1; // Set lesson access to 1 per day
        $freePlan->has_chat_access = false;
        $freePlan->boutique_discount = 0;
        $freePlan->boutique_free_shipping = false;
        $freePlan->has_cooking_space = false;
        $freePlan->invitation_to_events = false;
        $freePlan->referral_reward = false;
        $freePlan->save();
    }
}

