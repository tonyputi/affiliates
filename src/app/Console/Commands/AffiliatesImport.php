<?php

namespace App\Console\Commands;

use App\Models\Affiliate;
use Illuminate\Console\Command;

class AffiliatesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliates:import {file} {--p|prune}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import affiliates from file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('prune')) {
            Affiliate::truncate();
        }

        $file = $this->argument('file');

        if (!file_exists($file)) {
            return $this->error("Ooops! I'm unable to find {$file} file");
        }

        $file = fopen($file, 'r');

        while(!feof($file))
        {
            $payload = json_decode(fgets($file), true);
            Affiliate::create($payload);
        }
        
        fclose($file);

        return 0;
    }
}
