<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OsrsWikiApiService;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class GetLatest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osrs:latest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest item prices from OSRS Wiki API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Fetching latest prices from OSRS Wiki API...');

        $items = (new OsrsWikiApiService)->fetchLatest();

        $this->info('Found ' . count($items) . ' items.');
        $this->newLine();
        $this->line('Saving items to database...');

        $bar = $this->output->createProgressBar(count($items));
        $bar->start();
        
        foreach ($items as $id => $values) {
            $item = Item::where('item_id', $id)->first();

            if (!$item) {
                Log::info('Item ' . $id . ' not found in database while fetching latest prices.');
                $bar->advance();
                continue;
            }
            
            $latestPrice = $item->latestPrice();
            if ($latestPrice->high_time == $values['high_time'] &&
                $latestPrice->low_time == $values['low_time']
            ) {
                $bar->advance();
                continue;
            }

            $item->prices()->create([
                'high' => $values['high'],
                'high_time' => $values['highTime'],
                'low' => $values['low'],
                'low_time' => $values['lowTime'],
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info('Item prices saved successfully.');
    }
}
