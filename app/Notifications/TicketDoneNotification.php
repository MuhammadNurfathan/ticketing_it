<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketDoneNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("✅ Ticket {$this->ticket->ticket_code} Telah Selesai")
            ->greeting("Halo {$notifiable->name}! 👋")
            ->line("Kami senang memberitahu Anda bahwa ticket Anda telah **berhasil diselesaikan**.")
            ->line("---")
            ->line("**📋 Detail Ticket:**")
            ->line("**Kode Ticket:** `{$this->ticket->ticket_code}`")
            ->line("**Masalah:** {$this->ticket->problem}")
            ->when($this->ticket->solution, fn($mail) =>
                $mail->line("**✨ Solusi:** {$this->ticket->solution}")
            )
            ->line("---")
            ->line("Kami sangat menghargai feedback Anda untuk membantu kami meningkatkan layanan.")
            ->action('📝 Berikan Feedback', url('/DashboardTicketsUser'))
            ->line(" ")
            ->line("Terima kasih atas kepercayaan Anda! 🙏")
            ->salutation("Salam hangat Support Team");
    }
}
