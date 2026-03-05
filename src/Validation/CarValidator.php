<?php

declare(strict_types=1);

namespace App\Validation;

use InvalidArgumentException;

final class CarValidator
{
    public static function validate(array $payload): void
    {
        foreach (['name', 'make', 'model'] as $field) {
            if (!isset($payload[$field]) || !is_string($payload[$field]) || trim($payload[$field]) === '') {
                throw new InvalidArgumentException("{$field} is required and must be a non-empty string.");
            }
        }

        $year = $payload['year'] ?? null;
        $currentYear = (int) gmdate('Y');

        if (!is_int($year) && !(is_string($year) && ctype_digit($year))) {
            throw new InvalidArgumentException('year is required and must be an integer.');
        }

        $yearInt = (int) $year;
        if ($yearInt < 1886 || $yearInt > $currentYear) {
            throw new InvalidArgumentException("year must be between 1886 and {$currentYear}.");
        }
    }
}
