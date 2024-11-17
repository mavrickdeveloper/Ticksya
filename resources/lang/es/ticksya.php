<?php

return [
    'ticket' => [
        'status' => [
            'new' => 'Nuevo',
            'open' => 'Abierto',
            'pending' => 'Pendiente',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado',
        ],
        'priority' => [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'urgent' => 'Urgente',
        ],
        'fields' => [
            'ticket_number' => 'Número de Ticket',
            'title' => 'Título',
            'description' => 'Descripción',
            'category' => 'Categoría',
            'priority' => 'Prioridad',
            'status' => 'Estado',
            'assigned_to' => 'Asignado a',
            'created_by' => 'Creado por',
            'due_date' => 'Fecha de vencimiento',
            'is_private' => 'Ticket privado',
            'internal_notes' => 'Notas internas',
            'tags' => 'Etiquetas',
        ],
        'messages' => [
            'created' => 'Ticket creado exitosamente',
            'updated' => 'Ticket actualizado exitosamente',
            'deleted' => 'Ticket eliminado exitosamente',
            'restored' => 'Ticket restaurado exitosamente',
            'assigned' => 'Ticket asignado exitosamente',
            'status_changed' => 'Estado del ticket cambiado exitosamente',
            'overdue' => 'Ticket vencido',
            'due_soon' => 'Ticket próximo a vencer',
        ],
    ],
    'notifications' => [
        'ticket_created' => [
            'subject' => 'Nuevo Ticket Creado: #:ticket_number',
            'greeting' => 'Hola :name,',
            'message' => 'Se ha creado un nuevo ticket con los siguientes detalles:',
        ],
        'ticket_assigned' => [
            'subject' => 'Ticket Asignado: #:ticket_number',
            'greeting' => 'Hola :name,',
            'message' => 'Se te ha asignado el ticket #:ticket_number',
        ],
        'ticket_status_changed' => [
            'subject' => 'Estado del Ticket Cambiado: #:ticket_number',
            'greeting' => 'Hola :name,',
            'message' => 'El estado del ticket #:ticket_number ha cambiado de :old_status a :new_status',
        ],
        'ticket_comment' => [
            'subject' => 'Nuevo Comentario en Ticket: #:ticket_number',
            'greeting' => 'Hola :name,',
            'message' => 'Se ha añadido un nuevo comentario al ticket #:ticket_number',
        ],
        'ticket_due_soon' => [
            'subject' => 'Ticket Próximo a Vencer: #:ticket_number',
            'greeting' => 'Hola :name,',
            'message' => 'El ticket #:ticket_number vence en :hours horas',
        ],
    ],
    'workflow' => [
        'transitions' => [
            'new_to_open' => 'Abrir Ticket',
            'open_to_pending' => 'Marcar como Pendiente',
            'pending_to_open' => 'Reabrir Ticket',
            'open_to_resolved' => 'Resolver Ticket',
            'resolved_to_closed' => 'Cerrar Ticket',
            'reopen' => 'Reabrir Ticket',
        ],
    ],
    'reports' => [
        'metrics' => [
            'satisfaction_score' => 'Puntuación de Satisfacción',
            'response_time' => 'Tiempo Promedio de Respuesta',
            'resolution_time' => 'Tiempo Promedio de Resolución',
            'ticket_count' => 'Total de Tickets',
            'open_tickets' => 'Tickets Abiertos',
            'resolved_tickets' => 'Tickets Resueltos',
        ],
        'filters' => [
            'date_range' => 'Rango de Fechas',
            'category' => 'Categoría',
            'agent' => 'Agente',
            'status' => 'Estado',
            'priority' => 'Prioridad',
        ],
    ],
    'settings' => [
        'notifications' => [
            'title' => 'Preferencias de Notificación',
            'description' => 'Elige qué notificaciones deseas recibir',
            'email' => [
                'new_ticket' => 'Nuevo Ticket Creado',
                'ticket_assigned' => 'Ticket Asignado',
                'status_changed' => 'Estado Cambiado',
                'new_comment' => 'Nuevo Comentario',
                'due_soon' => 'Fecha de Vencimiento Próxima',
            ],
        ],
        'business_hours' => [
            'title' => 'Horario Laboral',
            'description' => 'Establece tu horario de operación',
            'timezone' => 'Zona Horaria',
            'days' => 'Días Laborales',
            'hours' => 'Horas Laborales',
        ],
    ],
];
