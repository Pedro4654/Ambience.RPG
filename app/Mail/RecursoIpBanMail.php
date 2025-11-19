<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecursoIpBanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, string $email)
    {
        $this->ticket = $ticket;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🚨 URGENTE: Novo Recurso de IP Ban - #' . $this->ticket->numero_ticket)
                    ->view('emails.recurso-ip-ban')
                    ->with([
                        'ticket' => $this->ticket,
                        'email_contato' => $this->email,
                        'usuario' => $this->ticket->usuario,
                        'url_ticket' => route('suporte.show', $this->ticket->id)
                    ]);
    }
}