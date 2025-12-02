<?php

declare(strict_types=1);

namespace App\Services\GlobalService\Export;

interface ExportInterface
{
    public function export($exportInstance, string $fileName): void;
}
