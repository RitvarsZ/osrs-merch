<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class OsrsWikiApiService {
    static function fetchItems() {
        $url = env('OSRS_API_URL') . '/mapping';
        $items = [];
        
        $response = Http::get($url);
        $response = $response->json();
        
        foreach ($response as $item) {
            $items[] = [
                'item_id' => $item['id'],
                'name' => $item['name'],
                'examine' => $item['examine'],
                'icon' => $item['icon'],
                'members' => $item['members'],
                'lowalch' => $item['lowalch'] ?? null,
                'highalch' => $item['highalch'] ?? null,
                'value' => $item['value'],
                'limit' => $item['limit'] ?? null,
            ];
        }
        
        return $items;
    }
}