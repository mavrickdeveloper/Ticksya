<?php

namespace Ticksya\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Ticksya\Models\Ticket;

abstract class BaseReport
{
    protected array $appliedFilters = [];
    
    abstract public function metrics(): array;
    abstract public function filters(): array;

    public function applyFilter(string $filter, $value): self
    {
        $this->appliedFilters[$filter] = $value;
        return $this;
    }

    public function getQuery(): Builder
    {
        $query = Ticket::query();

        foreach ($this->appliedFilters as $filter => $value) {
            match ($filter) {
                'date_range' => $this->applyDateRange($query, $value),
                'category' => $query->where('category_id', $value),
                'agent' => $query->where('assigned_to', $value),
                'priority' => $query->where('priority_id', $value),
                'status' => $query->where('status_id', $value),
                default => $this->applyCustomFilter($query, $filter, $value),
            };
        }

        return $query;
    }

    protected function applyDateRange(Builder $query, array $range): void
    {
        if (isset($range['start'])) {
            $query->where('created_at', '>=', $range['start']);
        }

        if (isset($range['end'])) {
            $query->where('created_at', '<=', $range['end']);
        }
    }

    protected function applyCustomFilter(Builder $query, string $filter, $value): void
    {
        // Override in child classes for custom filtering
    }

    public function generate(): Collection
    {
        $query = $this->getQuery();
        $metrics = $this->metrics();

        return collect($metrics)->mapWithKeys(function ($metric) use ($query) {
            return [$metric => $this->calculateMetric($metric, clone $query)];
        });
    }

    protected function calculateMetric(string $metric, Builder $query): mixed
    {
        return match ($metric) {
            'satisfaction_score' => $this->calculateSatisfactionScore($query),
            'response_time' => $this->calculateAverageResponseTime($query),
            'resolution_time' => $this->calculateAverageResolutionTime($query),
            'ticket_count' => $query->count(),
            'open_tickets' => $query->whereNull('resolved_at')->count(),
            'resolved_tickets' => $query->whereNotNull('resolved_at')->count(),
            default => $this->calculateCustomMetric($metric, $query),
        };
    }

    protected function calculateSatisfactionScore(Builder $query): float
    {
        return $query->whereNotNull('satisfaction_score')->avg('satisfaction_score') ?? 0.0;
    }

    protected function calculateAverageResponseTime(Builder $query): float
    {
        return $query->whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_response_time')
            ->value('avg_response_time') ?? 0.0;
    }

    protected function calculateAverageResolutionTime(Builder $query): float
    {
        return $query->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_resolution_time')
            ->value('avg_resolution_time') ?? 0.0;
    }

    protected function calculateCustomMetric(string $metric, Builder $query): mixed
    {
        // Override in child classes for custom metrics
        return null;
    }

    public function export(string $format = 'csv'): mixed
    {
        $data = $this->generate();

        return match ($format) {
            'csv' => $this->exportToCsv($data),
            'xlsx' => $this->exportToXlsx($data),
            'pdf' => $this->exportToPdf($data),
            default => throw new \InvalidArgumentException("Unsupported format: {$format}"),
        };
    }

    protected function exportToCsv(Collection $data): string
    {
        // CSV export implementation
        return '';
    }

    protected function exportToXlsx(Collection $data): string
    {
        // XLSX export implementation
        return '';
    }

    protected function exportToPdf(Collection $data): string
    {
        // PDF export implementation
        return '';
    }
}
