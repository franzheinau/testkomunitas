<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class EventController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:events.create')->only(['create','store']);
    }

    public function create()
    {
        // return view create event
        return view('events.create');
    }

    public function store(Request $request)
    {
        // validasi minimal
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'nullable|string|max:255',
        ]);

        // Simpan contoh (kamu harus membuat model/migration Event kalau mau persist)
        // \App\Models\Event::create($data + ['created_by' => auth()->id()]);

        return redirect()->route('dashboard')->with('success', 'Event (dummy) dibuat.');
    }
}
