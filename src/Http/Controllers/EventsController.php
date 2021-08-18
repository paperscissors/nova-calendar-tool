<?php

namespace Czemu\NovaCalendarTool\Http\Controllers;

use Czemu\NovaCalendarTool\Models\Event;
use App\SamplerHour;
use Illuminate\Http\Request;

class EventsController
{
    public function index(Request $request)
    {
        $events = SamplerHour::filter($request->query())
            ->get(['id', 'title', 'available_time']);

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validation = SamplerHour::getModel()->validate($request->input(), 'create');

        if ($validation->passes())
        {
            $event = SamplerHour::create($request->input());

            if ($event)
            {
                return response()->json([
                    'success' => true,
                    'event' => $event
                ]);
            }
        }

        return response()->json([
            'error' => true,
            'message' => $validation->errors()->first()
        ]);
    }

    public function update(Request $request, $eventId)
    {
        $event = SamplerHour::findOrFail($eventId);
        $validation = SamplerHour::getModel()->validate($request->input(), 'update');

        if ($validation->passes())
        {
            $event->update($request->input());

            return response()->json([
                'success' => true,
                'event' => $event
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => $validation->errors()->first()
        ]);
    }

    public function destroy(Request $request, $eventId)
    {
        $event = SamplerHour::findOrFail($eventId);

        if ( ! is_null($event))
        {
            $event->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }
}
