<?php

namespace Dennenboom\VerdantUI\Http\Controllers;

use Dennenboom\VerdantUI\Contracts\DynamicTablePreferencesStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TablePreferencesController extends Controller
{
    public function store(Request $request, string $key, DynamicTablePreferencesStore $store): JsonResponse
    {
        $validated = $request->validate([
            'visible_columns' => ['sometimes', 'array'],
            'visible_columns.*' => ['string'],
            'column_order' => ['sometimes', 'array'],
            'column_order.*' => ['string'],
        ]);

        $existing = $store->get($key) ?? [];

        $store->put($key, array_merge($existing, $validated));

        return response()->json(['saved' => true]);
    }
}
