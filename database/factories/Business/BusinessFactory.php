<?php

namespace Database\Factories\Business;

use App\Models\Business\Address;
use App\Models\Business\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BusinessFactory extends Factory {
    protected $model = Business::class;

    public function definition(): array {
        return [
            'name' => $this->faker->company(),
            'user_id' => User::whereDoesntHave('businesses')->first()->id,
            'address_id' => Address::factory()->create()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}