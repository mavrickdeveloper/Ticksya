<?php

namespace Ticksya\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Ticksya\Traits\HasCustomFields;
use Ticksya\Traits\HasTicketNotifications;

class Ticket extends Model
{
    use SoftDeletes, HasCustomFields, HasTicketNotifications;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'category_id',
        'priority_id',
        'status_id',
        'assigned_to',
        'created_by',
        'due_date',
        'is_private',
        'internal_notes',
        'first_response_at',
        'resolved_at',
        'satisfaction_score',
        'custom_fields',
        'tags',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_private' => 'boolean',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = static::generateTicketNumber();
            }
        });
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT';
        $year = date('Y');
        $lastTicket = static::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = $lastTicket ? (int)substr($lastTicket->ticket_number, -5) + 1 : 1;

        return sprintf('%s-%s-%05d', $prefix, $year, $sequence);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPriority::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    public function markAsResolved(): void
    {
        $this->resolved_at = now();
        $this->save();
    }

    public function markFirstResponse(): void
    {
        if (!$this->first_response_at) {
            $this->first_response_at = now();
            $this->save();
        }
    }

    public function isOverdue(): bool
    {
        return $this->due_date && Carbon::now()->isAfter($this->due_date);
    }

    public function getResponseTime(): ?int
    {
        if (!$this->first_response_at) {
            return null;
        }

        return $this->created_at->diffInMinutes($this->first_response_at);
    }

    public function getResolutionTime(): ?int
    {
        if (!$this->resolved_at) {
            return null;
        }

        return $this->created_at->diffInMinutes($this->resolved_at);
    }

    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->tags = $tags;
            $this->save();
        }
    }

    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        $this->tags = array_values(array_diff($tags, [$tag]));
        $this->save();
    }

    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    public function syncTags(array $tags): void
    {
        $this->tags = $tags;
        $this->save();
    }

    public function getWorkflow(): ?string
    {
        return $this->getCustomField('workflow');
    }

    public function setWorkflow(string $workflow): void
    {
        $this->setCustomField('workflow', $workflow);
        $this->save();
    }

    public function getSlaLevel(): ?string
    {
        return $this->getCustomField('sla_level');
    }

    public function setSlaLevel(string $level): void
    {
        $this->setCustomField('sla_level', $level);
        $this->save();
    }

    public function getBusinessFields(): array
    {
        $fields = [];
        $businessType = $this->getCustomField('business_type');

        if ($businessType) {
            switch ($businessType) {
                case 'ecommerce':
                    $fields = [
                        'order_number' => $this->getCustomField('order_number'),
                        'product_sku' => $this->getCustomField('product_sku'),
                        'order_value' => $this->getCustomField('order_value'),
                    ];
                    break;
                case 'financial':
                    $fields = [
                        'account_number' => $this->getCustomField('account_number'),
                        'transaction_ref' => $this->getCustomField('transaction_ref'),
                    ];
                    break;
                case 'realestate':
                    $fields = [
                        'property_id' => $this->getCustomField('property_id'),
                        'location' => $this->getCustomField('location'),
                        'tenant_info' => $this->getCustomField('tenant_info'),
                    ];
                    break;
            }
        }

        return $fields;
    }
}
