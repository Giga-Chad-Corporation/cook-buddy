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
        $masterPlan->lesson_access = -1; // Set lesson access to unlimited (-1)
        $masterPlan->has_chat_access = true;
        $masterPlan->boutique_discount = 5;
        $masterPlan->boutique_free_shipping = true;
        $masterPlan->has_cooking_space = true;
        $masterPlan->invitation_to_events = true;
        $masterPlan->referral_reward = true;
        $masterPlan->referral_reward_value = 5; // Set the referral reward value to €5
        $masterPlan->referral_reward_condition = 'new_subscriber';
        $masterPlan->referral_reward_condition_value = 1;
        $masterPlan->referral_reward_excluded_plans = ['Free'];
        $masterPlan->renewal_discount = 10; // Set the renewal discount to 10%
        $masterPlan->save();
    }
}

