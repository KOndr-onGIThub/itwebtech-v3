<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\LandingLead;
use App\Support\LeadMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class LandingLeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'gdpr' => 'required|accepted',
        ]);

        try {
            $lead = LandingLead::create([
                'name' => $data['name'],
                'company' => $data['company'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'budget' => $data['budget'] ?? null,
                'message' => $data['message'],
                'source' => 'landing.website-service',
                'mail_status' => LandingLead::MAIL_PENDING,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'lead' => __('landing.form.error'),
                ]);
        }

        // OND-264: notifikace stejnou cestou jako u ostatních formulářů.
        LeadMailer::notify($lead);

        return back()
            ->with('landing_lead_success', true);
    }
}
