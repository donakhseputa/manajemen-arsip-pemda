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
        $date = $this->faker->date();
        $year = date('Y', strtotime($date));
        $ymd = date('Ymd', strtotime($date));
        $type = $this->faker->randomElement([LetterType::INCOMING, LetterType::OUTGOING]);
        $typeCode = $type === LetterType::INCOMING->type() ? 'SM' : 'SK';
        $agendaNumber = sprintf('%s-%s-%s', $typeCode, $ymd,str_pad($this->faker->randomNumber(4), 4, '0', STR_PAD_LEFT));
        $classificationCodes = $this->faker->regexify('[0-9]{1}00\.1\.1');

        return [
            'reference_number' => $classificationCodes . '/' . $this->faker->regexify('/[0-9]{3}/(UM-KESRA|UM-PL)/[A-Z]{3}/(I|II|III|IV|V|VI|VII|VIII|IX|X|XI|XII)/') . '/' . $year,
            'agenda_number' => $agendaNumber,
            'from' => $this->faker->name('male'),
            'to' => $this->faker->name('female'),
            'letter_date' => $date,
            'received_date'=> $date,
            'description' => $this->faker->sentence(7),
            'note' => $this->faker->sentence(3),
            'year' => $year,
            'type' => $type->type(),
            'classification_code' => $classificationCodes,
            'user_id' => 1,
        ];
    }
}
