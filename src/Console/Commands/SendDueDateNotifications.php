<?php

namespace Ticksya\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Ticksya\Models\Ticket;
use Ticksya\Notifications\TicketDueSoonNotification;

class SendDueDateNotifications extends Command
{
    protected $signature = 'tickets:due-notifications';

    protected $description = 'Send notifications for tickets approaching their due date';

    public function handle()
    {
        // Get tickets due in the next 24 hours
        $tickets = Ticket::query()
            ->whereNotNull('due_date')
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addHours(24))
            ->whereNull('resolved_at')
            ->get();

        $count = 0;

        foreach ($tickets as $ticket) {
            $usersToNotify = collect([$ticket->creator]);

            if ($ticket->assigned_to) {
                $usersToNotify->push($ticket->assignee);
            }

            // Filter users based on their notification preferences
            $usersToNotify = $usersToNotify->filter(function ($user) {
                return $user->shouldReceiveTicketNotification('ticket_due_soon');
            });

            foreach ($usersToNotify as $user) {
                $user->notify(new TicketDueSoonNotification($ticket));
                $count++;
            }
        }

        $this->info("Sent {$count} due date notifications for {$tickets->count()} tickets.");
    }
}
