<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarsurfReservation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Carsurf-only mail theme (resources/views/mail/carsurf.css).
     *
     * Scoped to this mailable so the rest of the platform's mail keeps the
     * default theme.
     */
    public $theme = 'carsurf';

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public ?string $senderPhone,
        public string $senderMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Pedido de Reserva — Carsurf',
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.carsurf-reservation',
        );
    }
}
