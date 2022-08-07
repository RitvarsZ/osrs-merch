<?php

namespace App\Services;

use GdImage;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

    static function fetchIcon($icon): Response {
        $icon = str_replace(' ', '_', $icon);
        $url = 'https://oldschool.runescape.wiki/images/' . $icon;
        $response = Http::get($url);
        
        return $response;

        if ($response->status() == 200) {
            $file = imagecreatefromstring($response->body());
            $tmpFile = imagepng($file, storage_path('app/public/tmp/' . $icon));
            Storage::disk('public')->move('tmp/' . $icon, 'items/' . $icon);
            
            return $icon;
        }

        return '';        
    }
}