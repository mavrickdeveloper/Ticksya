<?php

namespace Ticksya\Workflows;

abstract class BaseWorkflow
{
    abstract public function defineSteps(): array;

    public function getAllowedTransitions(string $currentStep): array
    {
        $steps = $this->defineSteps();
        return $steps[$currentStep]['next'] ?? [];
    }

    public function isValidTransition(string $currentStep, string $newStep): bool
    {
        $allowedTransitions = $this->getAllowedTransitions($currentStep);
        return in_array($newStep, $allowedTransitions);
    }

    public function getStepName(string $step): string
    {
        $steps = $this->defineSteps();
        return $steps[$step]['name'] ?? $step;
    }

    public function getAllSteps(): array
    {
        return array_keys($this->defineSteps());
    }

    public function getInitialStep(): string
    {
        $steps = $this->defineSteps();
        return array_key_first($steps);
    }

    public function getFinalSteps(): array
    {
        $steps = $this->defineSteps();
        return array_keys(array_filter($steps, fn($step) => empty($step['next'])));
    }
}
