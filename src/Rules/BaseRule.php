<?php

namespace Ticksya\Rules;

use Ticksya\Models\Ticket;

abstract class BaseRule
{
    abstract public function conditions(): array;
    abstract public function actions(): array;

    public function evaluate(Ticket $ticket): bool
    {
        foreach ($this->conditions() as $field => $condition) {
            $operator = $condition[0];
            $value = $condition[1];

            $ticketValue = data_get($ticket, $field);

            switch ($operator) {
                case '=':
                case '==':
                    if ($ticketValue != $value) return false;
                    break;
                case '>':
                    if ($ticketValue <= $value) return false;
                    break;
                case '>=':
                    if ($ticketValue < $value) return false;
                    break;
                case '<':
                    if ($ticketValue >= $value) return false;
                    break;
                case '<=':
                    if ($ticketValue > $value) return false;
                    break;
                case 'in':
                    if (!in_array($ticketValue, (array) $value)) return false;
                    break;
                case 'not in':
                    if (in_array($ticketValue, (array) $value)) return false;
                    break;
                case 'contains':
                    if (!str_contains($ticketValue, $value)) return false;
                    break;
                case 'starts_with':
                    if (!str_starts_with($ticketValue, $value)) return false;
                    break;
                case 'ends_with':
                    if (!str_ends_with($ticketValue, $value)) return false;
                    break;
            }
        }

        return true;
    }

    public function apply(Ticket $ticket): void
    {
        if (!$this->evaluate($ticket)) {
            return;
        }

        $actions = $this->actions();

        foreach ($actions as $field => $value) {
            switch ($field) {
                case 'priority':
                    $ticket->priority_id = $value;
                    break;
                case 'status':
                    $ticket->status_id = $value;
                    break;
                case 'assign_to':
                    $ticket->assigned_to = $value;
                    break;
                case 'notify':
                    foreach ((array) $value as $userType) {
                        // Handle notifications based on user type
                        $this->notifyUser($ticket, $userType);
                    }
                    break;
                default:
                    if (is_callable($value)) {
                        $value($ticket);
                    } else {
                        $ticket->{$field} = $value;
                    }
            }
        }

        $ticket->save();
    }

    protected function notifyUser(Ticket $ticket, string $userType): void
    {
        // Implementation for different user types (manager, account_owner, etc.)
        switch ($userType) {
            case 'manager':
                // Notify managers
                break;
            case 'account_owner':
                // Notify account owner
                break;
            case 'assignee':
                // Notify assignee
                break;
        }
    }
}
