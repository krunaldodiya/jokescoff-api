<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearTmpImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listbees:clear-tmp-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all tmp images of posting';

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
        File::deleteDirectory(public_path('images/tmp'));
        File::makeDirectory(public_path('images/tmp'));

        return $this->info('Tmp images has been cleared.');
    }
}
