<?php

namespace Database\Factories;

use Core\Domain\Entities\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CastMember>
 */
class CastMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $types = [CastMemberType::ACTOR, CastMemberType::DIRECTOR];

        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->name(),
            'type' => $types[array_rand($types)],
        ];
    }
}
