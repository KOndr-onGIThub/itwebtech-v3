<?php

namespace App\Http\Controllers;

use App\Models\LandingLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class HomeLeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        try {
            LandingLead::create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'] ?? null,
                'message'    => $data['message'],
                'source'     => 'home.inline',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'lead' => __('home.inline_form.error'),
                ])
                ->with('home_lead_target', 'poptavka');
        }

        return back()
            ->with('home_lead_success', true)
            ->with('home_lead_target', 'poptavka');
    }
}
