<?php
namespace App\Exports\Reports\Consumers;

use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ConsumerQExport implements FromQuery, ShouldQueue, WithChunkReading
{
    use Exportable;

    public function query()
    {
        return ConsumerDocument::query();
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
