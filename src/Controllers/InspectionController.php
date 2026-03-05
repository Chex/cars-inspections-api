<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\InspectionService;

final class InspectionController
{
    public function __construct(private readonly InspectionService $service)
    {
    }

    public function index(): array
    {
        return $this->service->listInspections();
    }

    public function store(array $payload): array
    {
        return $this->service->createInspection($payload);
    }
}
