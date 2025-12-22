<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class QrMenuController extends Controller
{
    public function show($token)
    {
        // Simple token validation (you can enhance this)
        if ($token !== config('app.qr_menu_token', 'menu123')) {
            abort(404);
        }

        $categories = Category::active()
            ->ordered()
            ->with(['activeItems' => function ($query) {
                $query->ordered();
            }])
            ->get();

        return view('qr-menu.show', compact('categories'));
    }
}
