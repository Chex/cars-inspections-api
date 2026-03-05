<?php

declare(strict_types=1);

namespace App\Repositories;

class CarRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = dirname(__DIR__, 2) . '/storage/cars.json';
    }

    public function all(): array
    {
        return $this->read();
    }

    public function create(array $car): array
    {
        $cars = $this->read();
        $ids = array_column($cars, 'id');
        $car['id'] = $ids === [] ? 1 : ((int) max($ids) + 1);
        $cars[] = $car;
        $this->write($cars);

        return $car;
    }

    public function exists(int $id): bool
    {
        foreach ($this->read() as $car) {
            if (($car['id'] ?? null) === $id) {
                return true;
            }
        }

        return false;
    }

    private function read(): array
    {
        if (!is_file($this->file)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($this->file), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function write(array $cars): void
    {
        file_put_contents(
            $this->file,
            json_encode($cars, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}
