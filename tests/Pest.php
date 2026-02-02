<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

pest()->extend(Tests\TestCase::class)
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

// Helper function to generate valid student IDs (alphanumeric, 5-20 chars)
function generateValidStudentId(): string
{
    $length = fake()->numberBetween(5, 20);
    return fake()->regexify('[A-Z0-9]{' . $length . '}');
}

// Helper function to generate valid full names (alphabetic with spaces, 2-100 chars)
function generateValidFullName(): string
{
    $firstName = fake()->firstName();
    $lastName = fake()->lastName();
    
    // Remove any non-alphabetic characters (like apostrophes)
    $firstName = preg_replace('/[^a-zA-Z]/', '', $firstName);
    $lastName = preg_replace('/[^a-zA-Z]/', '', $lastName);
    
    $fullName = $firstName . ' ' . $lastName;
    
    // Ensure it's within bounds
    if (strlen($fullName) > 100) {
        $fullName = substr($fullName, 0, 100);
    }
    
    return $fullName;
}

// Helper function to generate valid courses (alphabetic with spaces, 2-100 chars)
function generateValidCourse(): string
{
    $courses = [
        'Computer Science',
        'Information Technology',
        'Business Administration',
        'Engineering',
        'Nursing',
        'Education',
        'Psychology',
        'Biology',
        'Mathematics',
        'Physics'
    ];
    
    return fake()->randomElement($courses);
}

// Helper function to generate invalid full names (violates alphabetic + spaces, 2-100 chars rule)
function generateInvalidFullName(): string
{
    $invalidTypes = [
        'too_short' => 'A', // Less than 2 chars
        'too_long' => str_repeat('A', 101), // More than 100 chars
        'with_numbers' => 'John Doe 123', // Contains numbers
        'with_special' => 'John@Doe', // Contains special characters
        'empty' => '', // Empty string
    ];
    
    return fake()->randomElement($invalidTypes);
}

// Helper function to generate invalid student IDs (violates alphanumeric, 5-20 chars rule)
function generateInvalidStudentId(): string
{
    $invalidTypes = [
        'too_short' => 'A123', // Less than 5 chars
        'too_long' => str_repeat('A', 21), // More than 20 chars
        'with_special' => 'ABC-123', // Contains special characters
        'with_spaces' => 'ABC 123', // Contains spaces
        'empty' => '', // Empty string
    ];
    
    return fake()->randomElement($invalidTypes);
}

// Helper function to generate invalid courses (violates alphabetic + spaces, 2-100 chars rule)
function generateInvalidCourse(): string
{
    $invalidTypes = [
        'too_short' => 'A', // Less than 2 chars
        'too_long' => str_repeat('A', 101), // More than 100 chars
        'with_numbers' => 'Course 101', // Contains numbers
        'with_special' => 'Course@Test', // Contains special characters
        'empty' => '', // Empty string
    ];
    
    return fake()->randomElement($invalidTypes);
}

// Helper function to generate invalid year levels (violates 1-6 range)
function generateInvalidYearLevel()
{
    $invalidTypes = [
        0, // Below minimum
        7, // Above maximum
        -1, // Negative
        100, // Way above maximum
        'not_a_number', // Not a number
    ];
    
    return fake()->randomElement($invalidTypes);
}

// Helper function to generate invalid contact numbers (violates numeric, 10-15 digits rule)
function generateInvalidContactNumber(): string
{
    $invalidTypes = [
        'too_short' => '123456789', // Less than 10 digits
        'too_long' => '1234567890123456', // More than 15 digits
        'with_letters' => '12345abcde', // Contains letters
        'with_special' => '123-456-7890', // Contains special characters
        'with_spaces' => '123 456 7890', // Contains spaces
    ];
    
    return fake()->randomElement($invalidTypes);
}

// Helper function to generate invalid addresses (violates alphanumeric, 5-255 chars rule)
function generateInvalidAddress(): string
{
    $invalidTypes = [
        'too_short' => 'Addr', // Less than 5 chars
        'too_long' => str_repeat('A', 256), // More than 255 chars
    ];
    
    return fake()->randomElement($invalidTypes);
}
