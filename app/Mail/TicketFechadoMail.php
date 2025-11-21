<?php

// ===== app/Mail/TicketFechadoMail.php =====

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketFechadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $observacao;

    public function __construct(Ticket $ticket, $observacao = null)
    {
        $this->ticket = $ticket;
        $this->observacao = $observacao;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ Ticket #{$this->ticket->numero_ticket} Fechado - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-fechado',
            with: [
                'ticket' => $this->ticket,
                'observacao' => $this->observacao,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}