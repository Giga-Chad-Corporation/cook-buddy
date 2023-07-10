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
        $starterPlan = new Plan();
        $starterPlan->name = 'Starter';
        $starterPlan->monthly_price = 9.90;
        $starterPlan->annual_price = 113;
        $starterPlan->has_ads = false;
        $starterPlan->can_comment = true;
        $starterPlan->lesson_access = 5; // Set lesson access to 5 per day
        $starterPlan->has_chat_access = true;
        $starterPlan->boutique_discount = 5;
        $starterPlan->boutique_free_shipping = true; // Set boutique_free_shipping to true
        $starterPlan->has_cooking_space = false;
        $starterPlan->invitation_to_events = true;
        $starterPlan->referral_reward = true;
        $starterPlan->referral_reward_value = 5; // Set the reward value to €5
        $starterPlan->referral_reward_condition = 'new_subscriber';
        $starterPlan->referral_reward_condition_value = 3;
        $starterPlan->referral_reward_excluded_plans = ['Free'];
        $starterPlan->save();
    }
}

