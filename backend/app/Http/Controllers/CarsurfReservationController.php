<?php

namespace App\Http\Controllers;

use App\Mail\CarsurfReservation;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CarsurfReservationController extends Controller
{
    public function show()
    {
        $recipients = SiteSetting::carsurfReservasRecipients();

        return view('pages.carsurf.reservas', [
            'contactEmail' => $recipients[0],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create([
            'entity' => 'carsurf',
            'type' => 'reserva',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        Mail::to(SiteSetting::carsurfReservasRecipients())
            ->queue(new CarsurfReservation(
                senderName: $validated['name'],
                senderEmail: $validated['email'],
                senderPhone: $validated['phone'] ?? null,
                senderMessage: $validated['message'],
            ));

        return redirect()->back()->with('success', __('messages.carsurf.reservas.form.success'));
    }
}
