<?php

namespace Ticksya\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ticksya\Models\Ticket;
use Ticksya\Models\TicketComment;

class TicketCommentAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public TicketComment $comment
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = config('app.url') . '/admin/tickets/' . $this->ticket->id;
        $commenter = $this->comment->user->name;

        return (new MailMessage)
            ->subject("New Comment on Ticket: #{$this->ticket->ticket_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("{$commenter} has added a comment to ticket #{$this->ticket->ticket_number}:")
            ->line("Title: {$this->ticket->title}")
            ->line("Comment:")
            ->line($this->comment->content)
            ->action('View Ticket', $url)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'comment_id' => $this->comment->id,
            'commenter_id' => $this->comment->user_id,
            'commenter_name' => $this->comment->user->name,
            'type' => 'comment_added',
        ];
    }
}
