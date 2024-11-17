<?php

namespace Ticksya\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ticksya\Models\Ticket;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = config('app.url') . '/admin/tickets/' . $this->ticket->id;

        return (new MailMessage)
            ->subject("New Ticket Created: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new ticket has been created:")
            ->line("Ticket: #{$this->ticket->ticket_number}")
            ->line("Title: {$this->ticket->title}")
            ->line("Priority: {$this->ticket->priority->name}")
            ->line("Category: {$this->ticket->category->name}")
            ->action('View Ticket', $url)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'type' => 'ticket_created',
        ];
    }
}
