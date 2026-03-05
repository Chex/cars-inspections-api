<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\CarService;
use App\Repositories\CarRepository;

class CarServiceTest extends TestCase
{
    public function testCanCreateCar()
    {
        // Arrange: prepare payload and a mock repository that returns the saved car
        $payload = [
            'name' => 'Test Sedan',
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2018,
        ];

        $expected = $payload;
        $expected['id'] = 1;

        $repo = $this->createMock(CarRepository::class);
        $repo->expects($this->once())
             ->method('create')
             ->with($this->callback(fn($arg) =>
                 $arg['name'] === trim($payload['name']) &&
                 $arg['make'] === trim($payload['make'])
             ))
             ->willReturn($expected);

        $service = new CarService($repo);

        // Act
        $result = $service->createCar($payload);

        // Assert
        $this->assertSame($expected, $result);
    }
}
