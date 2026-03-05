<?php

declare(strict_types=1);

namespace App\Validation;

use InvalidArgumentException;

final class InspectionValidator
{
    public static function validate(array $payload): void
    {
        $carId = $payload['carId'] ?? null;
        if (!is_int($carId) && !(is_string($carId) && ctype_digit($carId))) {
            throw new InvalidArgumentException('carId is required and must be an integer.');
        }

        foreach (['wipers', 'engineSound', 'headlights'] as $field) {
            if (!array_key_exists($field, $payload) || !is_bool($payload[$field])) {
                throw new InvalidArgumentException("{$field} is required and must be a boolean.");
            }
        }
    }
}
