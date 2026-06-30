<?php

namespace App\Mail;

use App\Models\RwBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RwBroadcastNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $broadcast;

    /**
     * Create a new message instance.
     */
    public function __construct(RwBroadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengumuman RW Baru: ' . $this->broadcast->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rw_broadcast',
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
