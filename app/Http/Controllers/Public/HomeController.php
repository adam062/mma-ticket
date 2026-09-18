<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TicketType;

class HomeController extends Controller
{
    public function index()
    {
        $ticketTypes = TicketType::where('is_active', true)->orderBy('sort_order')->get();
        $eventNameEn = Setting::cached('event', 'name_en', 'MMA Championship 2026');
        $eventNameAr = Setting::cached('event', 'name_ar', 'بطولة MMA ٢٠٢٦');
        $eventDate = Setting::cached('event', 'date', '2026-12-15');
        $eventTime = Setting::cached('event', 'time', '20:00');
        $eventLocationEn = Setting::cached('event', 'location_en', 'Cairo, Egypt');
        $eventLocationAr = Setting::cached('event', 'location_ar', 'القاهرة، مصر');
        $eventDescEn = Setting::cached('event', 'description_en', '');
        $eventDescAr = Setting::cached('event', 'description_ar', '');

        return view('public.home', compact(
            'ticketTypes',
            'eventNameEn',
            'eventNameAr',
            'eventDate',
            'eventTime',
            'eventLocationEn',
            'eventLocationAr',
            'eventDescEn',
            'eventDescAr'
        ));
    }
}
