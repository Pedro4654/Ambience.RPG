<?php
// ===== app/Mail/TicketStatusAlteradoMail.php =====

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketStatusAlteradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $statusAntigo;
    public $statusNovo;

    public function __construct(Ticket $ticket, $statusAntigo, $statusNovo)
    {
        $this->ticket = $ticket;
        $this->statusAntigo = $statusAntigo;
        $this->statusNovo = $statusNovo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔄 Status do Ticket #{$this->ticket->numero_ticket} Alterado - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-status-alterado',
            with: [
                'ticket' => $this->ticket,
                'statusAntigo' => $this->statusAntigo,
                'statusNovo' => $this->statusNovo,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}