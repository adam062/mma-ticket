<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        if (! in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        session(['locale' => $locale]);

        return back();
    }
}
