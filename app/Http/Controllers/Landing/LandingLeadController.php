<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LandingLeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'gdpr' => 'required|accepted',
        ]);

        // TODO: Hand off to mail/CRM integration once lead-processing flow exists.

        return back()
            ->with('landing_lead_success', true);
    }
}
