<?php

namespace Jiannius\Ipay88;

use App\Http\Controllers\Controller;

class Ipay88Controller extends Controller
{
    public function checkout()
    {
        $url = request()->url;
        $body = request()->body;

        return view('ipay88::checkout', [
            'url' => $url,
            'body' => $body,
        ]);
    }
}