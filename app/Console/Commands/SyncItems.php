<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OsrsWikiApiService;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class SyncItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osrs:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync items from OSRS Wiki API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Fetching items from OSRS Wiki API...');

        $items = (new OsrsWikiApiService)->fetchItems();

        $this->info('Found ' . count($items) . ' items.');
        $this->newLine();
        $this->line('Saving items to database...');

        $bar = $this->output->createProgressBar(count($items));
        $bar->start();
        
        foreach ($items as $item) {
            $existingItem = Item::where('item_id', $item['item_id'])->first();

            if (Storage::disk('public')->exists('/items/' . $item['item_id'] . '.png') == false) {
                $iconResponse = (new OsrsWikiApiService)->fetchIcon($item['icon']);
                if ($iconResponse->status() == 200) {
                    $file = imagecreatefromstring($iconResponse->body());
                    $file = imagepng($file, storage_path('app/public/tmp/' . $item['item_id'] . '.png'));
                    Storage::disk('public')->move('tmp/' . $item['item_id'] . '.png', 'items/' . $item['item_id'] . '.png');
                    $item['icon'] = '/items/' . $item['item_id'] . '.png';
                } else {
                    $item['icon'] = '';
                }
            }            

            if ($existingItem) {
                $existingItem->update($item);
            } else {
                Item::create($item);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info('Items successfully synced.');
    }
}
