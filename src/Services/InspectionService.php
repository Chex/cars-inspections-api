<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CarRepository;
use App\Repositories\InspectionRepository;
use App\Validation\InspectionValidator;
use RuntimeException;

final class InspectionService
{
    public function __construct(
        private readonly InspectionRepository $inspections,
        private readonly CarRepository $cars
    ) {
    }

    public function listInspections(): array
    {
        return $this->inspections->all();
    }

    public function createInspection(array $payload): array
    {
        InspectionValidator::validate($payload);

        $carId = (int) $payload['carId'];
        if (!$this->cars->exists($carId)) {
            throw new RuntimeException('Car does not exist.');
        }

        return $this->inspections->create([
            'carId' => $carId,
            'wipers' => (bool) $payload['wipers'],
            'engineSound' => (bool) $payload['engineSound'],
            'headlights' => (bool) $payload['headlights'],
            'performedAt' => gmdate(DATE_ATOM),
        ]);
    }
}
