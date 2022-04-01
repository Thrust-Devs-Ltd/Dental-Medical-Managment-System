<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Spatie\Backup\Events\BackupZipWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSuccessfulDatabaseBackup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BackupZipWasCreated $event
     * @return void
     */
    public function handle(BackupZipWasCreated $event)
    {
        $this->mailBackupFile($event->pathToZip);
    }

    public function mailBackupFile($path)
    {
        try {
            Mail::raw('You have a new database backup file.', function ($message) use ($path) {
                $message->to(env('DB_BACKUP_EMAIL'))
                    ->subject('DB Auto-backup Done')
                    ->attach($path);
            });
        } catch (\Exception $exception) {
            throw $exception;
        }

    }
}
