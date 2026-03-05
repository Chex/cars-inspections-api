<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CarService;

final class CarController
{
    public function __construct(private readonly CarService $service)
    {
    }

    public function index(): array
    {
        return $this->service->listCars();
    }

    public function store(array $payload): array
    {
        return $this->service->createCar($payload);
    }
}
