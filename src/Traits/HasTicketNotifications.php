<?php

namespace Ticksya\Traits;

trait HasTicketNotifications
{
    public function getTicketNotificationPreferences(): array
    {
        return $this->notification_preferences ?? [
            'ticket_created' => true,
            'ticket_assigned' => true,
            'ticket_status_changed' => true,
            'ticket_commented' => true,
            'ticket_due_soon' => true,
        ];
    }

    public function shouldReceiveTicketNotification(string $type): bool
    {
        $preferences = $this->getTicketNotificationPreferences();
        return $preferences[$type] ?? true;
    }

    public function updateTicketNotificationPreferences(array $preferences): void
    {
        $this->notification_preferences = array_merge(
            $this->getTicketNotificationPreferences(),
            $preferences
        );
        $this->save();
    }

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
}
