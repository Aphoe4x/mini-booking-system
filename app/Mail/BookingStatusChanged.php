<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $previousStatus = ''
    ) {
        //
    }

    public function envelope(): Envelope
    {
        $status = $this->booking->status;

        return match ($status) {
            'confirmed' => new Envelope(
                subject: 'Booking Confirmed — ' . $this->booking->service_name,
            ),
            'cancelled' => new Envelope(
                subject: 'Booking Cancelled — ' . $this->booking->service_name,
            ),
            default => new Envelope(
                subject: 'Booking Request Received — ' . $this->booking->service_name,
            ),
        };
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
