<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'tel'     => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'gdpr'    => 'required|accepted',
        ]);

        // TODO: send email via Mail::to(config('mail.contact_address'))->send(new ContactMessage($data));

        return response()->json(['message' => __('contact.message_success')]);
    }
}
