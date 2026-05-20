<?php

namespace Database\Factories;

use App\Enums\LetterType;
use App\Models\Letter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Letter>
 */
class LetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = date('Y');
        $classificationCodes = $this->faker->regexify('[0-9]{1}00\.1\.1');

        return [
            'reference_number' => $classificationCodes . '/' . $this->faker->regexify('/[0-9]{3}/(UM-KESRA|UM-PL)/[A-Z]{3}/(I|II|III|IV|V|VI|VII|VIII|IX|X|XI|XII)/') . '/' . $year,
            'agenda_number' => $this->faker->randomNumber(5),
            'from' => $this->faker->name('male'),
            'to' => $this->faker->name('female'),
            'letter_date' => $this->faker->date(),
            'received_date'=> $this->faker->date(),
            'description' => $this->faker->sentence(7),
            'note' => $this->faker->sentence(3),
            'year' => $year,
            'type' => $this->faker->randomElement([LetterType::INCOMING->type(), LetterType::OUTGOING->type()]),
            'classification_code' => $classificationCodes,
            'user_id' => 1,
        ];
    }
}
