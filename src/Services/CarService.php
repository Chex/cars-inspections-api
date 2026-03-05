<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CarRepository;
use App\Validation\CarValidator;

final class CarService
{
    public function __construct(private readonly CarRepository $cars)
    {
    }

    public function listCars(): array
    {
        return $this->cars->all();
    }

    public function createCar(array $payload): array
    {
        CarValidator::validate($payload);

        return $this->cars->create([
            'name' => trim((string) $payload['name']),
            'make' => trim((string) $payload['make']),
            'model' => trim((string) $payload['model']),
            'year' => (int) $payload['year'],
        ]);
    }
}
