<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExploreController extends Controller
{
    const PANES = [
        'sales' => 0,
        'zoning' => 1,
        'construction' => 2,
        'alteration' => 3,
    ];

    public function index(string $pane = null)
    {
        return inertia('Explore', [
            'pane' => self::PANES[$pane] ?? null,
        ]);
    }
}
