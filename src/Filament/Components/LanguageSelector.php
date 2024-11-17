<?php

namespace Ticksya\Filament\Components;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Ticksya\Traits\HasLocalization;

class LanguageSelector extends Component
{
    use HasLocalization;

    public string $currentLocale;

    public function mount()
    {
        $this->currentLocale = $this->getCurrentLocale();
    }

    public function updatedCurrentLocale($value)
    {
        if ($this->isValidLocale($value)) {
            $this->setLocale($value);
            $this->redirect(request()->header('Referer'));
        }
    }

    public function render()
    {
        if (!Config::get('ticksya.localization.show_language_selector', true)) {
            return;
        }

        return view('ticksya::components.language-selector', [
            'locales' => $this->getAvailableLocales(),
            'currentLocale' => $this->currentLocale,
        ]);
    }

    public static function make(): Select
    {
        return Select::make('locale')
            ->options(Config::get('ticksya.localization.available_locales', ['en' => 'English']))
            ->default(app()->getLocale())
            ->reactive()
            ->afterStateUpdated(function ($state) {
                if (app()->getLocale() !== $state) {
                    app()->setLocale($state);
                    redirect()->refresh();
                }
            });
    }
}
