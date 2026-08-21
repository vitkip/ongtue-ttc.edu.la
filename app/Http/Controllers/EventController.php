<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __invoke(): Response
    {
        $mapEvent = fn (Event $event) => [
            ...$event->toArray(),
            'image_url' => $event->resolved_image_url,
        ];

        $allEvents = Event::where('is_active', true)
            ->orderBy('event_date')
            ->get()
            ->map($mapEvent);

        $upcomingEvents = Event::where('is_active', true)
            ->whereDate('event_date', '>=', Carbon::today())
            ->orderBy('event_date')
            ->get()
            ->map($mapEvent);

        $featuredEvent = $upcomingEvents->firstWhere('is_featured', true);

        return Inertia::render('Events', [
            'featuredEvent' => $featuredEvent,
            'upcomingEvents' => $upcomingEvents
                ->reject(fn (array $event) => $featuredEvent && $event['id'] === $featuredEvent['id'])
                ->values(),
            'allEvents' => $allEvents,
        ]);
    }
}
