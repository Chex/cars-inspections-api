<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\InspectionService;
use App\Repositories\CarRepository;
use App\Repositories\InspectionRepository;
use RuntimeException;

class InspectionServiceTest extends TestCase
{
    public function testCannotCreateInspectionForNonExistentCar()
    {
        // Arrange: Mock repositories
        $nonExistentId = 9999;
        $carRepo = $this->createMock(CarRepository::class);
        $carRepo->method('exists')
                ->with($nonExistentId)
                ->willReturn(false);

        $inspectionRepo = $this->createMock(InspectionRepository::class);

        $service = new InspectionService($inspectionRepo, $carRepo);

        $payload = [
            'carId' => $nonExistentId,
            'wipers' => true,
            'engineSound' => true,
            'headlights' => true
        ];

        // Act & Assert: Expect exception
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Car does not exist.');

        $service->createInspection($payload);
    }
}