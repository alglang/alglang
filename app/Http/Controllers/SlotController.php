<?php

namespace App\Http\Controllers;

use App\Models\Slot;

class SlotController extends Controller
{
    public function show(Slot $slot): \Illuminate\View\View
    {
        return view('slots.show', ['slot' => $slot]);
    }
}
