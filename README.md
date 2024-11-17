# Ticksya - Advanced FilamentPHP Support Ticketing System

A comprehensive, customizable support ticketing system built for FilamentPHP with advanced notification, workflow, and reporting capabilities.

## Features
 
- 🌐 Multi-language Support
  - Automatic language detection
  - User language preferences
  - Easy translation management
  - Built-in English and Spanish translations
- 🎨 Highly Customizable
  - Custom fields support
  - Industry-specific adaptations
  - Flexible workflows
  - Configurable notifications
- 📊 Advanced Reporting
  - Custom metric calculations
  - Flexible filtering
  - Multiple export formats
  - Scheduled reporting
- 🔔 Smart Notifications
  - Customizable triggers
  - Multiple channels
  - Template support
  - Scheduled notifications
- ⚙️ Workflow Management
  - Custom workflow definitions
  - Transition validation
  - State management
  - Automated actions

## Requirements

- PHP 8.2+
- Laravel 11.x
- FilamentPHP 3.2+

## Installation

1. Install the package via composer:
```bash
composer require mavrickdeveloper/ticksya
```

2. Run the installation command:
```bash
php artisan ticksya:install
```

This will:
- Publish the configuration file
- Publish the migration files
- Ask if you want to run migrations immediately
- Set up the necessary database tables

3. If you didn't run migrations during installation, run them manually:
```bash
php artisan migrate
```

4. Add the plugin to your Filament panel provider (typically `app/Providers/Filament/AdminPanelProvider.php`):
```php
use Ticksya\TicksyaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... your other panel configuration
        ->plugin(TicksyaPlugin::make());
}
```

## Configuration

After installation, you can customize the package behavior by modifying the published configuration file:

```bash
config/ticksya.php
```

1. Publish the translations (optional):

```bash
php artisan vendor:publish --tag="ticksya-translations"
```

2. Configure your environment variables in `.env`:

```env
# Core Settings
TICKSYA_QUEUE_CONNECTION=redis
TICKSYA_CACHE_DRIVER=redis

# Notification Settings
TICKSYA_NOTIFICATIONS_ENABLED=true
TICKSYA_NOTIFICATION_CHANNELS=["mail","slack"]

# Workflow Settings
TICKSYA_DEFAULT_WORKFLOW=standard
TICKSYA_WORKFLOW_VALIDATION=true

# Language Settings
TICKSYA_DEFAULT_LOCALE=en
TICKSYA_FALLBACK_LOCALE=en
TICKSYA_AUTO_DETECT_LOCALE=true
TICKSYA_USE_BROWSER_LOCALE=true
TICKSYA_SHOW_LANGUAGE_SELECTOR=true
```

## Usage

### Basic Setup

1. Add the necessary traits to your User model:

```php
use Ticksya\Traits\HasTickets;
use Ticksya\Traits\HasTicketNotifications;

class User extends Authenticatable
{
    use HasTickets, HasTicketNotifications;
}
```

2. Register the plugin in your Panel provider:

```php
use Ticksya\TicksyaPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                TicksyaPlugin::make(),
            ]);
    }
}
```

### Multi-language Support

1. Enable language support in your models:

```php
use Ticksya\Traits\HasLocalization;

class YourModel extends Model
{
    use HasLocalization;
}
```

2. Add the language selector to your Filament resources:

```php
use Ticksya\Filament\Components\LanguageSelector;

class YourResource extends Resource
{
    public function getHeaderActions(): array
    {
        return [
            LanguageSelector::make(),
        ];
    }
}
```

3. Add new languages:
   - Create translation file: `resources/lang/{locale}/ticksya.php`
   - Add locale to config: `config/ticksya.php`

### Custom Fields

1. Define custom fields in config:

```php
// config/ticksya.php
'custom_fields' => [
    'healthcare' => [
        'patient_id' => [
            'type' => 'string',
            'required' => true,
            'validation' => 'required|string|max:255',
        ],
    ],
],
```

2. Use custom fields in your tickets:

```php
$ticket->setCustomField('patient_id', 'P12345');
```

### Custom Workflows

1. Create a workflow class:

```php
use Ticksya\Workflows\BaseWorkflow;

class HealthcareWorkflow extends BaseWorkflow
{
    public function configure(): void
    {
        $this->addState('triage')
            ->addState('diagnosis')
            ->addState('treatment')
            ->addTransition('triage', 'diagnosis')
            ->addTransition('diagnosis', 'treatment');
    }
}
```

2. Register the workflow:

```php
// config/ticksya.php
'workflows' => [
    'healthcare' => HealthcareWorkflow::class,
],
```

## Customization

### Regional Adaptations

- Timezone configurations
- Date/time format support
- Localization options
- Currency settings

### Industry-Specific Features

- Healthcare (HIPAA compliance)
- E-commerce support
- Financial services
- Real estate management

### Workflow Management

- Custom state definitions
- Transition rules
- Automated actions
- SLA monitoring

### Reporting Capabilities

- Custom metrics
- Scheduled reports
- Export formats
- Dashboard widgets

## Security

- Role-based access control
- Custom field validation
- Secure data storage
- Audit logging

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
