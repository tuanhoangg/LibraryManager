<?php

namespace App\Jobs;

use App\Imports\BookImport;
use App\Imports\UsersImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ImportBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $jobId;

    public function __construct($filePath, $jobId)
    {
        $this->filePath = $filePath;
        $this->jobId = $jobId;
    }

    public function handle()
    {
        // Process the import
        Excel::import(new BookImport($this->jobId), storage_path('app/' . $this->filePath));

        // Delete the temporary file after processing
        Storage::delete($this->filePath);
    }

    public function failed(\Exception $exception)
    {
        // Log the failure and update the cache with error status
        Log::error("Import job failed: " . $exception->getMessage());
        Cache::put('import_status_' . $this->jobId, [
            'status' => 'error',
            'message' => 'Import job failed: ' . $exception->getMessage()
        ], now()->addMinutes(10));
    }
}
