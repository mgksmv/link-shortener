<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function redirect(string $code, Request $request)
    {
        $link = Link::query()->where('code', $code)->firstOrFail();

        LinkVisit::query()->create([
            'link_id' => $link->id,
            'ip_address' => $request->ip(),
            'visited_at' => now(),
        ]);

        return redirect()->away($link->original_url);
    }
}
