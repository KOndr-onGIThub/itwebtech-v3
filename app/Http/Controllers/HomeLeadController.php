<?php

namespace App\Http\Controllers;

use App\Models\LandingLead;
use App\Support\Honeypot;
use App\Support\LeadMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class HomeLeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // OND-280: Past na boty. Před validací, ať bot z odpovědi nepozná nic.
        // `source` se tu čte ještě nezvalidovaný — používá se jen na to, který
        // formulář ohlásí úspěch, nikam se neukládá.
        if (Honeypot::tripped($request, (string) $request->input('source', 'home.inline'))) {
            return $this->success($request->input('source') === 'home.faq');
        }

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'message' => 'required|string|max:5000',
            // OND-121 T18 — FAQ mikro-formulář se hlásí jako home.faq.
            // Ostatní zdroje se mapují na home.inline (default).
            'source'  => 'nullable|string|in:home.inline,home.faq',
        ]);

        $source = $data['source'] ?? 'home.inline';
        $isFaq  = $source === 'home.faq';

        try {
            $lead = LandingLead::create([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'phone'       => $data['phone'] ?? null,
                'message'     => $data['message'],
                'source'      => $source,
                'mail_status' => LandingLead::MAIL_PENDING,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'lead' => __('home.inline_form.error'),
                ])
                ->with('home_lead_target', $isFaq ? 'faq' : 'poptavka');
        }

        // OND-264: dřív tady notifikace vůbec nebyla — poptávka skončila
        // v tabulce, do které se nikdo nedívá. Selhání mailu nesmí shodit
        // odpověď uživateli, lead je bezpečně uložený.
        LeadMailer::notify($lead);

        return $this->success($isFaq);
    }

    /**
     * Úspěšná odpověď. Sdílená schválně: past na boty musí vracet přesně to
     * samé co skutečné odeslání, jinak by šlo z odpovědi poznat, že sklapla.
     */
    private function success(bool $isFaq): RedirectResponse
    {
        return back()
            ->with($isFaq ? 'faq_lead_success' : 'home_lead_success', true)
            ->with('home_lead_target', $isFaq ? 'faq' : 'poptavka');
    }
}
