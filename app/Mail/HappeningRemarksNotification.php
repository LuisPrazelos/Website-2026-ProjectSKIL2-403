<?php

namespace App\Mail;

use App\Models\Happening;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HappeningRemarksNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $happening;
    public $remarks;

    /**
     * Create a new message instance.
     */
    public function __construct(Happening $happening, string $remarks)
    {
        $this->happening = $happening;
        $this->remarks = $remarks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Opmerkingen ontvangen voor je evenement #' . $this->happening->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.happening-remarks',
            with: [
                'happening' => $this->happening,
                'remarks' => $this->remarks,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
