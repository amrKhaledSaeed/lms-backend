<?php

declare(strict_types=1);

namespace App\Services\GlobalService\Export;

use App\Services\GlobalService\Export\ExportInterface;


class PdfExport implements ExportInterface
{
    public function export($exportClass, string $fileName): void
    {
        // Implement the logic to export data to PDF
        // For example, you can use a library like Dompdf or Snappy to generate PDFs
        // Here, we will just simulate the export process with a simple message

        dd('Exporting data to pdf');
    }
}
