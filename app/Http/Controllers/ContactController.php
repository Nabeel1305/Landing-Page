<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the contact page. Kept as a real controller method (rather than
     * Route::view) so the GET and POST for /contact.html live together.
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle a contact form submission.
     *
     * This stores the submission so nothing is lost even before real email
     * delivery is configured, then makes a best-effort attempt to notify by
     * mail. With MAIL_MAILER=log (the default in .env.example), that mail is
     * written to storage/logs/laravel.log rather than actually sent — wire up
     * real SMTP credentials in .env when you're ready to receive these for
     * real, and this starts working with no code changes.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'reason' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::create($validated);

        try {
            $recipient = config('mail.from.address');

            if ($recipient) {
                Mail::raw(
                    "New contact form submission from {$submission->name} <{$submission->email}>\n".
                    "Reason: {$submission->reason}\n\n".
                    $submission->message,
                    function ($mail) use ($recipient, $submission) {
                        $mail->to($recipient)
                            ->subject('PakaPay contact form: '.($submission->reason ?: 'General inquiry'))
                            ->replyTo($submission->email, $submission->name);
                    }
                );
            }
        } catch (\Throwable $e) {
            // A failed notification email should never lose the submission —
            // it's already saved above. Just log it and move on.
            Log::warning('Contact form email notification failed: '.$e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', "Thanks, {$submission->name} — we've got your message and will get back to you.");
    }
}
