<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Newsletter Subscriber - Miravil Dental',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.admin-notification',
            with: [
                'email' => $this->subscriber->email,
                'subscribedAt' => $this->subscriber->subscribed_at->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
