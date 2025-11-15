<?php

// ===== app/Mail/TicketAtribuidoMail.php =====

namespace App\Mail;

use App\Models\Ticket;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketAtribuidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $staff;

    public function __construct(Ticket $ticket, Usuario $staff)
    {
        $this->ticket = $ticket;
        $this->staff = $staff;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "📋 Ticket #{$this->ticket->numero_ticket} Atribuído a Você - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-atribuido',
            with: [
                'ticket' => $this->ticket,
                'staff' => $this->staff,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
