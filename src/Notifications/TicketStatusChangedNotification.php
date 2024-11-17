<?php

namespace Ticksya\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ticksya\Models\Ticket;

class TicketStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = config('app.url') . '/admin/tickets/' . $this->ticket->id;

        return (new MailMessage)
            ->subject("Ticket Status Updated: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("The status of ticket #{$this->ticket->ticket_number} has been updated:")
            ->line("Title: {$this->ticket->title}")
            ->line("Previous Status: {$this->oldStatus}")
            ->line("New Status: {$this->newStatus}")
            ->action('View Ticket', $url)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'status_changed',
        ];
    }
}
