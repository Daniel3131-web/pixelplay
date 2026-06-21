<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function searchEvents(string $search = null): Collection|array
    {
        $query = Event::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->latest()->get();
    }

    public function getEventWithTournaments(int $id): Event
    {
        return Event::with('tournaments')->findOrFail($id);
    }

    public function createEvent(array $data): Event
    {
        return Event::create($data);
    }

    public function updateEvent(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    public function deleteEvent(Event $event): void
    {
        $event->delete();
    }
}
