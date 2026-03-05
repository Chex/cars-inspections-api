<?php

declare(strict_types=1);

namespace App\Repositories;

class InspectionRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = dirname(__DIR__, 2) . '/storage/inspections.json';
    }

    public function all(): array
    {
        return $this->read();
    }

    public function create(array $inspection): array
    {
        $inspections = $this->read();
        $ids = array_column($inspections, 'id');
        $inspection['id'] = $ids === [] ? 1 : ((int) max($ids) + 1);
        $inspections[] = $inspection;
        $this->write($inspections);

        return $inspection;
    }

    private function read(): array
    {
        if (!is_file($this->file)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($this->file), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function write(array $inspections): void
    {
        file_put_contents(
            $this->file,
            json_encode($inspections, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }
}
