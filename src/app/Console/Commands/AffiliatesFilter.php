<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;

class AffiliatesFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliates:filter 
                            {file : The file containing the affiliates} 
                            {--r|range=100 : The maximum distance range}
                            {--c|city=dublin : The city to refer to for the calculation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Filter affiliates by range and city';

    /**
     * The latitude and longitude of the cities
     *
     * @var string
     */
    protected $cities = [
        'malta' => [
            'latitude' => 35.89972, 
            'longitude' => 14.51472
        ],
        'dublin' => [
            'latitude' => 53.3340285, 
            'longitude' => -6.2535495
        ]
    ];

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
        $file = $this->argument('file');
        $range = $this->option('range');
        $city = $this->option('city');
        $collection = collect();

        if (!file_exists($file)) {
            return $this->error("Ooops! I'm unable to find {$file} file");
        }

        $file = fopen($file, 'r');

        while(!feof($file))
        {
            $affiliate = json_decode(fgets($file), true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \Exception(json_last_error_msg(), json_last_error());
            }

            // calculating ditance on the fly
            $distance = great_circle_distance(
                $affiliate['latitude'], 
                $affiliate['longitude'], 
                $this->cities[$city]['latitude'], 
                $this->cities[$city]['longitude']
            ) / 1000;

            // comparing distance
            if ($distance <= $range) {
                $collection->push(Arr::only($affiliate, ['affiliate_id', 'name']));
            }
        }
        
        fclose($file);

        $this->table(['ID', 'Name'], $collection->sortBy('affiliate_id')->toArray());

        return 0;
    }
}
