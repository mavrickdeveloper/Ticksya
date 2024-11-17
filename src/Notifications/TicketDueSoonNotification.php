<?php

namespace Ticksya\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ticksya\Models\Ticket;

class TicketDueSoonNotification extends Notification implements ShouldQueue
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
        $hoursLeft = now()->diffInHours($this->ticket->due_date);

        return (new MailMessage)
            ->subject("Ticket Due Soon: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A ticket is approaching its due date:")
            ->line("Ticket: #{$this->ticket->ticket_number}")
            ->line("Title: {$this->ticket->title}")
            ->line("Due in: {$hoursLeft} hours")
            ->line("Priority: {$this->ticket->priority->name}")
            ->action('View Ticket', $url)
            ->line('Please take necessary action to meet the deadline.');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'due_date' => $this->ticket->due_date,
            'type' => 'ticket_due_soon',
        ];
    }
}
