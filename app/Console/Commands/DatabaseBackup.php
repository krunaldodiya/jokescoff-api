<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listbees:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will take Database backup automatically.';

    /**
     * DatabaseBackup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        $ignore = 'listbees.migrations';
        $filename = Carbon::now()->format('Y-M-d_h:i') . '.sql';

        $command = "mysqldump -u {$username} --password={$password} {$database} --ignore-table={$ignore} > $filename";
        $process = new Process($command);
        $process->start();

        while ($process->isRunning()) {
            Storage::disk(env('STORAGE_DISK'))->put('database_backup/' . $filename, file_get_contents($filename));
            unlink($filename);
        }

        $this->info($command);
    }
}
