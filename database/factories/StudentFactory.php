<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a valid full name (only letters and spaces)
        $firstName = preg_replace('/[^a-zA-Z]/', '', $this->faker->firstName());
        $lastName = preg_replace('/[^a-zA-Z]/', '', $this->faker->lastName());
        $fullName = $firstName . ' ' . $lastName;
        
        // Generate a valid address (ensure it's within 5-255 chars)
        $address = $this->faker->address();
        if (strlen($address) > 255) {
            $address = substr($address, 0, 255);
        }
        if (strlen($address) < 5) {
            $address = $this->faker->streetAddress();
        }
        
        return [
            'student_id' => $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'full_name' => $fullName,
            'course' => $this->faker->randomElement([
                'Computer Science',
                'Information Technology',
                'Business Administration',
                'Engineering',
                'Nursing',
            ]),
            'year_level' => $this->faker->numberBetween(1, 6),
            'contact_number' => $this->faker->numerify('##########'),
            'address' => $address,
            'photo_path' => null,
        ];
    }
}
