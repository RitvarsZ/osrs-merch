<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $options = [
            'search' => $request->search ?? '',
            'sortBy' => $request->sortBy ?? 'name',
            'sortType' => $request->sortType ?? 'asc',
            'per_page' => $request->per_page ?? 25,
        ];

        $items = Item::where('name', 'like', '%' . $options['search'] . '%')
            ->orderBy($options['sortBy'], $options['sortType'])
            ->paginate($options['per_page']);

        return Inertia::render('Items/Index', ['data' => $items]);
    }

    public function show(Item $item)
    {
        return Inertia::render('Items/Show', $item);
    }
}
