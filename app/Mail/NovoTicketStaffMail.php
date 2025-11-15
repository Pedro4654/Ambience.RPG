<?php

// ===== app/Mail/NovoTicketStaffMail.php =====

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovoTicketStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        $subject = $this->ticket->ehDenuncia() 
            ? "🚨 Nova Denúncia - Ticket #{$this->ticket->numero_ticket}"
            : "📩 Novo Ticket #{$this->ticket->numero_ticket}";

        return new Envelope(
            subject: $subject . " - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.novo-ticket-staff',
            with: ['ticket' => $this->ticket]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}