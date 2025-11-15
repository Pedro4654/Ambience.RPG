<?php 

// ===== app/Mail/TicketPrioridadeAlteradaMail.php =====

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketPrioridadeAlteradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $prioridadeAnterior;
    public $prioridadeNova;

    public function __construct(Ticket $ticket, $prioridadeAnterior, $prioridadeNova)
    {
        $this->ticket = $ticket;
        $this->prioridadeAnterior = $prioridadeAnterior;
        $this->prioridadeNova = $prioridadeNova;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⚠️ Prioridade do Ticket #{$this->ticket->numero_ticket} Alterada - Ambience RPG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-prioridade-alterada',
            with: [
                'ticket' => $this->ticket,
                'prioridadeAnterior' => $this->prioridadeAnterior,
                'prioridadeNova' => $this->prioridadeNova,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}