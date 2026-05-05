<?php

namespace App\Jobs;

use App\Models\Admin\User;
use App\Notifications\ExportNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AfterExportJob implements ShouldQueue
{
    use Dispatchable, Queueable;
    protected $exportId;

    public function __construct($exportId)
    {
        $this->exportId = $exportId;
    }

    public function handle()
    {
        // Get export record
        $export = DB::table('adm_user_exports')
            ->where('id', $this->exportId)
            ->first();

        if (!$export) return;

        // Update status
        DB::table('adm_user_exports')
            ->where('id', $this->exportId)
            ->update(['status' => 1]);

        // Send notification
        $user = User::find($export->user_id);

        if ($user) {
            $user->notify(new ExportNotification($this->exportId,$export->file_name ?? null));
        }
    }
}