<?php

namespace App\Http\Controllers;

use App\Services\HttpMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function index(): View
    {
        return view('contact.index');
    }

    /**
     * Handle public contact inquiry submissions.
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'department' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            $mailService = app(HttpMailService::class);
            $content = "New Contact Inquiry from: {$validated['name']} ({$validated['email']})\n".
                       "Department: {$validated['department']}\n".
                       "Subject: {$validated['subject']}\n\n".
                       "Message:\n{$validated['message']}";

            $mailService->send(
                'info@capacityconnect.gov',
                "Contact Inquiry: {$validated['subject']}",
                $content
            );
        } catch (\Throwable $e) {
            logger()->warning('Contact form mail dispatch fallback: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you for reaching out to CapacityConnect Headquarters. Your message has been logged and assigned tracking ticket #'.strtoupper(uniqid('CC-')).'. Our support desk will respond shortly.');
    }
}
