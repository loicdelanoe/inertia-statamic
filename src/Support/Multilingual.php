<?php

namespace InertiaStatamic\InertiaStatamic\Support;

use Illuminate\Support\Uri;

class Multilingual
{
    public static function enabled(): bool
    {
        return (bool) config('inertia-statamic.multi_lingual');
    }

    public static function setCurrentLocale(string $locale)
    {
        app()->setLocale($locale);
    }

    /**
     * Extracts the locale from the first segment of a given path.
     *
     * This method assumes that the first URI segment represents a locale
     * (e.g. "/en", "/fr"). If the path contains at least one segment, the
     * first one is returned; otherwise, null is returned.
     *
     * @param  string  $path  The URI path from which to extract the locale.
     * @return string|null The first path segment interpreted as a locale, or null if none exists.
     */
    public static function getLocaleByPath(string $path): ?string
    {
        $firstSegment = Uri::of($path)->pathSegments()->first();

        $supportedLocales = config('inertia-statamic.supported_locales');

        return in_array($firstSegment, $supportedLocales) ? $firstSegment : null;
    }
}
