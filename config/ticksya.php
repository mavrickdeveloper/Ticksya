<?php

return [
    'date_format' => [
        'display' => env('TICKSYA_DATE_FORMAT', 'd/m/Y'),
        'time_format' => env('TICKSYA_TIME_FORMAT', 'H:i'),
    ],
    'timezone' => [
        'default' => env('TICKSYA_DEFAULT_TIMEZONE', 'UTC'),
        'user_specific' => env('TICKSYA_USER_TIMEZONE', true),
    ],
    'hipaa' => [
        'enabled' => env('TICKSYA_HIPAA_ENABLED', false),
        'audit_trail' => env('TICKSYA_HIPAA_AUDIT', true),
        'phi_encryption' => env('TICKSYA_HIPAA_ENCRYPTION', true),
        'access_controls' => [
            'role_based' => true,
            'department_based' => true,
        ],
    ],
    'workflows' => [],
    'custom_fields' => [
        'text' => [
            'validation' => 'string|max:255',
            'component' => 'text-input',
        ],
        'number' => [
            'validation' => 'numeric',
            'component' => 'number-input',
        ],
        'select' => [
            'validation' => 'string',
            'component' => 'select-input',
        ],
        'date' => [
            'validation' => 'date',
            'component' => 'date-input',
        ],
        'file' => [
            'validation' => 'file',
            'component' => 'file-input',
        ],
    ],
    'sla' => [
        'enabled' => env('TICKSYA_SLA_ENABLED', true),
        'levels' => [
            'premium' => [
                'first_response' => env('TICKSYA_SLA_PREMIUM_RESPONSE', 1),
                'resolution' => env('TICKSYA_SLA_PREMIUM_RESOLUTION', 24),
                'business_hours' => true,
            ],
            'standard' => [
                'first_response' => env('TICKSYA_SLA_STANDARD_RESPONSE', 4),
                'resolution' => env('TICKSYA_SLA_STANDARD_RESOLUTION', 48),
                'business_hours' => true,
            ],
        ],
        'business_hours' => [
            'timezone' => env('TICKSYA_BUSINESS_TIMEZONE', 'UTC'),
            'days' => explode(',', env('TICKSYA_BUSINESS_DAYS', 'Monday,Tuesday,Wednesday,Thursday,Friday')),
            'hours' => [
                env('TICKSYA_BUSINESS_START', '09:00'),
                env('TICKSYA_BUSINESS_END', '17:00'),
            ],
        ],
    ],
    'integrations' => [
        'salesforce' => [
            'enabled' => env('TICKSYA_SALESFORCE_ENABLED', false),
            'sync_fields' => [
                'ticket_number' => 'Case_Number__c',
                'title' => 'Subject',
            ],
        ],
        'hubspot' => [
            'enabled' => env('TICKSYA_HUBSPOT_ENABLED', false),
            'sync_direction' => env('TICKSYA_HUBSPOT_SYNC', 'bidirectional'),
        ],
    ],
    'channels' => [
        'email' => [
            'enabled' => env('TICKSYA_EMAIL_ENABLED', true),
            'incoming_mail_server' => env('TICKSYA_MAIL_SERVER'),
        ],
        'slack' => [
            'enabled' => env('TICKSYA_SLACK_ENABLED', false),
            'webhook_url' => env('TICKSYA_SLACK_WEBHOOK'),
        ],
        'teams' => [
            'enabled' => env('TICKSYA_TEAMS_ENABLED', false),
            'tenant_id' => env('TICKSYA_TEAMS_TENANT'),
        ],
    ],
    'cache' => [
        'enabled' => env('TICKSYA_CACHE_ENABLED', true),
        'ttl' => env('TICKSYA_CACHE_TTL', 3600),
        'tags' => ['tickets', 'users', 'reports'],
    ],
    'queues' => [
        'notifications' => env('TICKSYA_QUEUE_NOTIFICATIONS', 'notifications'),
        'reports' => env('TICKSYA_QUEUE_REPORTS', 'reports'),
        'exports' => env('TICKSYA_QUEUE_EXPORTS', 'exports'),
        'sync' => env('TICKSYA_QUEUE_SYNC', 'sync'),
    ],
    'exports' => [
        'formats' => ['csv', 'xlsx', 'pdf'],
        'scheduling' => [
            'enabled' => true,
            'frequencies' => ['daily', 'weekly', 'monthly'],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Localization Settings
    |--------------------------------------------------------------------------
    |
    | Configure the language settings for your ticketing system.
    | The default locale will be used as a fallback when the current locale is not available.
    |
    */
    'localization' => [
        'default_locale' => env('TICKSYA_DEFAULT_LOCALE', 'en'),
        'fallback_locale' => env('TICKSYA_FALLBACK_LOCALE', 'en'),
        'available_locales' => [
            'en' => 'English',
            'es' => 'Español',
            // Add more languages here
        ],
        'auto_detect_locale' => env('TICKSYA_AUTO_DETECT_LOCALE', true),
        'use_browser_locale' => env('TICKSYA_USE_BROWSER_LOCALE', true),
        'show_language_selector' => env('TICKSYA_SHOW_LANGUAGE_SELECTOR', true),
    ],
];
