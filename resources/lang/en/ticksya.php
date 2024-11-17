<?php

return [
    'ticket' => [
        'status' => [
            'new' => 'New',
            'open' => 'Open',
            'pending' => 'Pending',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ],
        'priority' => [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ],
        'fields' => [
            'ticket_number' => 'Ticket Number',
            'title' => 'Title',
            'description' => 'Description',
            'category' => 'Category',
            'priority' => 'Priority',
            'status' => 'Status',
            'assigned_to' => 'Assigned To',
            'created_by' => 'Created By',
            'due_date' => 'Due Date',
            'is_private' => 'Private Ticket',
            'internal_notes' => 'Internal Notes',
            'tags' => 'Tags',
        ],
        'messages' => [
            'created' => 'Ticket created successfully',
            'updated' => 'Ticket updated successfully',
            'deleted' => 'Ticket deleted successfully',
            'restored' => 'Ticket restored successfully',
            'assigned' => 'Ticket assigned successfully',
            'status_changed' => 'Ticket status changed successfully',
            'overdue' => 'Ticket is overdue',
            'due_soon' => 'Ticket is due soon',
        ],
    ],
    'notifications' => [
        'ticket_created' => [
            'subject' => 'New Ticket Created: #:ticket_number',
            'greeting' => 'Hello :name,',
            'message' => 'A new ticket has been created with the following details:',
        ],
        'ticket_assigned' => [
            'subject' => 'Ticket Assigned: #:ticket_number',
            'greeting' => 'Hello :name,',
            'message' => 'You have been assigned to ticket #:ticket_number',
        ],
        'ticket_status_changed' => [
            'subject' => 'Ticket Status Changed: #:ticket_number',
            'greeting' => 'Hello :name,',
            'message' => 'The status of ticket #:ticket_number has been changed from :old_status to :new_status',
        ],
        'ticket_comment' => [
            'subject' => 'New Comment on Ticket: #:ticket_number',
            'greeting' => 'Hello :name,',
            'message' => 'A new comment has been added to ticket #:ticket_number',
        ],
        'ticket_due_soon' => [
            'subject' => 'Ticket Due Soon: #:ticket_number',
            'greeting' => 'Hello :name,',
            'message' => 'Ticket #:ticket_number is due in :hours hours',
        ],
    ],
    'workflow' => [
        'transitions' => [
            'new_to_open' => 'Open Ticket',
            'open_to_pending' => 'Mark as Pending',
            'pending_to_open' => 'Reopen Ticket',
            'open_to_resolved' => 'Resolve Ticket',
            'resolved_to_closed' => 'Close Ticket',
            'reopen' => 'Reopen Ticket',
        ],
    ],
    'reports' => [
        'metrics' => [
            'satisfaction_score' => 'Satisfaction Score',
            'response_time' => 'Average Response Time',
            'resolution_time' => 'Average Resolution Time',
            'ticket_count' => 'Total Tickets',
            'open_tickets' => 'Open Tickets',
            'resolved_tickets' => 'Resolved Tickets',
        ],
        'filters' => [
            'date_range' => 'Date Range',
            'category' => 'Category',
            'agent' => 'Agent',
            'status' => 'Status',
            'priority' => 'Priority',
        ],
    ],
    'settings' => [
        'notifications' => [
            'title' => 'Notification Preferences',
            'description' => 'Choose which notifications you want to receive',
            'email' => [
                'new_ticket' => 'New Ticket Created',
                'ticket_assigned' => 'Ticket Assigned',
                'status_changed' => 'Status Changed',
                'new_comment' => 'New Comment',
                'due_soon' => 'Due Date Approaching',
            ],
        ],
        'business_hours' => [
            'title' => 'Business Hours',
            'description' => 'Set your business operating hours',
            'timezone' => 'Timezone',
            'days' => 'Working Days',
            'hours' => 'Working Hours',
        ],
    ],
];
