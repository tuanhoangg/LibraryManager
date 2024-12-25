<?php

namespace App\Console\Commands;

use App\Services\LateReturnService;
use Illuminate\Console\Command;

class UpdateLateReturnStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-late-return-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update late return status';

    /**
     * Execute the console command.
     */
    public function handle(LateReturnService $lateReturnService)
    {
        //
        $lateReturnService->updateLateReturn();
    }
}
