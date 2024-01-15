<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use MatanYadaev\EloquentSpatial\Objects\Point;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Event::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validated();

        $newEvent = new Event;
        $newEvent->name = $validated['name'];
        $newEvent->description = $validated['description'];
        $newEvent->capacity = $validated['capacity'];
        $newEvent->location = $validated['location'];
        $newEvent->location_coords = new Point($validated['location_coords_lat'], $validated['location_coords_long']);
        $newEvent->held_date = $validated['held_date'];
        $newEvent->save();

        return response()->json($newEvent, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validated();

        return response()->json($event->update($validated));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        return response()->json($event->delete());
    }
}
