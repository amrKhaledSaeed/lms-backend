<?php

declare(strict_types=1);

namespace App\Services\GlobalService\Export;

use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\NotifyOnCompletedExport;
use Illuminate\Support\Facades\Auth;

class ExcelExport implements ExportInterface
{
    public function export($exportClass, string $fileName): void
    {
        $filePath = "exports/{$fileName}_" . now()->format('Y_m_d_His') . ".xlsx";
        Excel::queue($exportClass, $filePath, 'public')->chain([
            new NotifyOnCompletedExport($filePath, Auth::user())
        ]);
    }
}
