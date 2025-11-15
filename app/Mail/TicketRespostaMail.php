<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketResposta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketRespostaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $resposta;

    public function __construct(Ticket $ticket, TicketResposta $resposta)
    {
        $this->ticket = $ticket;
        $this->resposta = $resposta;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "💬 Nova Resposta no Ticket #{$this->ticket->numero_ticket} - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-resposta',
            with: [
                'ticket' => $this->ticket,
                'resposta' => $this->resposta,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}