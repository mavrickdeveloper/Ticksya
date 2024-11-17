<?php

namespace Ticksya\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ticksya\Models\Ticket;

class TicketAssignedNotification extends Notification implements ShouldQueue
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
            ->subject("Ticket Assigned: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned to ticket #{$this->ticket->ticket_number}:")
            ->line("Title: {$this->ticket->title}")
            ->line("Priority: {$this->ticket->priority->name}")
            ->line("Category: {$this->ticket->category->name}")
            ->line("Status: {$this->ticket->status->name}")
            ->action('View Ticket', $url)
            ->line('Please review the ticket and take appropriate action.');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'type' => 'ticket_assigned',
        ];
    }
}
