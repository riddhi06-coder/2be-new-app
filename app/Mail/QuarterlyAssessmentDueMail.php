<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class QuarterlyAssessmentDueMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \Illuminate\Support\Collection<int,\App\Models\User> */
    public Collection $employees;

    public function __construct(Collection $employees)
    {
        $this->employees = $employees;
    }

    public function envelope(): Envelope
    {
        $count = $this->employees->count();

        return new Envelope(
            subject: $count === 1
                ? 'Employee due for quarterly assessment'
                : $count.' employees due for quarterly assessment',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'backend.email.quarterly_assessment_due',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
