<?php

namespace Ticksya\Traits;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;

trait HasCustomFields
{
    public function initializeHasCustomFields(): void
    {
        $this->casts['custom_fields'] = AsCollection::class;
    }

    public function getCustomField(string $field)
    {
        return data_get($this->custom_fields, $field);
    }

    public function setCustomField(string $field, $value): void
    {
        $customFields = $this->custom_fields ?? collect();
        $customFields[$field] = $value;
        $this->custom_fields = $customFields;
    }

    public function validateCustomField(string $field, $value): bool
    {
        $fieldConfig = config("ticksya.custom_fields.{$field}");

        if (!$fieldConfig) {
            return false;
        }

        $validator = validator(
            ['field' => $value],
            ['field' => $fieldConfig['validation']]
        );

        return !$validator->fails();
    }

    public function getCustomFieldComponent(string $field): ?string
    {
        return config("ticksya.custom_fields.{$field}.component");
    }

    public function getAllCustomFields(): Collection
    {
        return $this->custom_fields ?? collect();
    }

    public function clearCustomField(string $field): void
    {
        $customFields = $this->custom_fields ?? collect();
        $customFields->forget($field);
        $this->custom_fields = $customFields;
    }

    public function hasCustomField(string $field): bool
    {
        return $this->custom_fields?->has($field) ?? false;
    }

    public function syncCustomFields(array $fields): void
    {
        $this->custom_fields = collect($fields);
    }
}
