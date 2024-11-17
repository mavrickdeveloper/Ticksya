<?php

namespace Ticksya\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

trait HasLocalization
{
    /**
     * Get the current locale for the application.
     *
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Get the fallback locale from config.
     *
     * @return string
     */
    public function getFallbackLocale(): string
    {
        return Config::get('ticksya.localization.fallback_locale', 'en');
    }

    /**
     * Get available locales from config.
     *
     * @return array
     */
    public function getAvailableLocales(): array
    {
        return Config::get('ticksya.localization.available_locales', ['en' => 'English']);
    }

    /**
     * Check if a locale is valid.
     *
     * @param string $locale
     * @return bool
     */
    public function isValidLocale(string $locale): bool
    {
        return array_key_exists($locale, $this->getAvailableLocales());
    }

    /**
     * Get a translated value for a given key.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?: $this->getCurrentLocale();
        
        if (!$this->isValidLocale($locale)) {
            $locale = $this->getFallbackLocale();
        }

        return trans("ticksya::$key", $replace, $locale);
    }

    /**
     * Get a translated value with fallback.
     *
     * @param string $key
     * @param array $replace
     * @return string
     */
    public function transWithFallback(string $key, array $replace = []): string
    {
        $value = $this->trans($key, $replace);

        if ($value === $key) {
            return $this->trans($key, $replace, $this->getFallbackLocale());
        }

        return $value;
    }

    /**
     * Set the application's locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void
    {
        if ($this->isValidLocale($locale)) {
            App::setLocale($locale);
        }
    }

    /**
     * Get the language name for a locale code.
     *
     * @param string $locale
     * @return string|null
     */
    public function getLanguageName(string $locale): ?string
    {
        return $this->getAvailableLocales()[$locale] ?? null;
    }

    /**
     * Auto-detect and set the locale based on browser preferences.
     *
     * @return string
     */
    public function autoDetectLocale(): string
    {
        if (!Config::get('ticksya.localization.auto_detect_locale', true)) {
            return $this->getCurrentLocale();
        }

        $browserLocales = request()->getLanguages();
        
        foreach ($browserLocales as $browserLocale) {
            $locale = substr($browserLocale, 0, 2);
            if ($this->isValidLocale($locale)) {
                $this->setLocale($locale);
                return $locale;
            }
        }

        return $this->getCurrentLocale();
    }
}
