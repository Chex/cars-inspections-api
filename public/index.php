<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Support/helpers.php';

use App\Controllers\CarController;
use App\Controllers\InspectionController;
use App\Repositories\CarRepository;
use App\Repositories\InspectionRepository;
use App\Services\CarService;
use App\Services\InspectionService;

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

$carRepository = new CarRepository();
$inspectionRepository = new InspectionRepository();

$carService = new CarService($carRepository);
$inspectionService = new InspectionService($inspectionRepository, $carRepository);

$carController = new CarController($carService);
$inspectionController = new InspectionController($inspectionService);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

try {
    $payload = read_json_payload();

    if ($method === 'GET' && $uri === '/cars') {
        json_response($carController->index());
        return;
    }

    if ($method === 'POST' && $uri === '/cars') {
        json_response($carController->store($payload), 201);
        return;
    }

    if ($method === 'GET' && $uri === '/inspections') {
        json_response($inspectionController->index());
        return;
    }

    if ($method === 'POST' && $uri === '/inspections') {
        json_response($inspectionController->store($payload), 201);
        return;
    }

    json_response([
        'error' => [
            'code' => 'NOT_FOUND',
            'message' => 'Route not found.',
        ],
    ], 404);

} catch (InvalidArgumentException $exception) {

    json_response([
        'error' => [
            'code' => 'VALIDATION_ERROR',
            'message' => $exception->getMessage(),
        ],
    ], 400);

} catch (RuntimeException $exception) {

    json_response([
        'error' => [
            'code' => 'BUSINESS_RULE_ERROR',
            'message' => $exception->getMessage(),
        ],
    ], 404);

} catch (Throwable $exception) {
    
    json_response([
        'error' => [
            'code' => 'INTERNAL_ERROR',
            'message' => 'Unexpected server error.',
        ],
    ], 500);
}
