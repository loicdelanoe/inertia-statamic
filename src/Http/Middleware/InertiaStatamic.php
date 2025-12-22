<?php

namespace InertiaStatamic\InertiaStatamic\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Inertia\Inertia;
use Statamic\Entries\Entry;
use Statamic\Structures\Page;

class InertiaStatamic
{
    /**
     * Return an Inertia response containing the Statamic data.
     *
     * @return \Inertia\Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $this->normalizePath($request->path());

        if (config('inertia-statamic.multi_lingual')) {
            $locale = Uri::of($path)->pathSegments()->first();

            if (in_array($locale, config('inertia-statamic.supported_locales'))) {
                app()->setLocale($locale);
            }
        }

        $page = Entry::findByUri($path);

        if ($this->shouldSkipRequest($page)) {
            return $next($request);
        }

        $request->attributes->set('page', $page);

        return Inertia::render(
            $this->buildComponentPath($page),
            ['content' => $page->toAugmentedCollection()]
        );
    }

    /**
     * Build the path for the component based on Pages Blueprint Name
     */
    protected function buildComponentPath($entry): string
    {
        return $entry->blueprint['title'];
    }

    /**
     * Normalize the given path.
     */
    protected function normalizePath(string $path): string
    {
        if ($path === '/' || $path === '') {
            return '/';
        }

        return Str::start($path, '/');
    }

    protected function isInvalidPage($page): bool
    {
        return ! ($page instanceof Page || $page instanceof Entry);
    }

    protected function isUnauthorized($page): bool
    {
        return ! $page->published() && ! Auth::check();
    }

    protected function shouldSkipRequest($page): bool
    {
        return $this->isInvalidPage($page) || $this->isUnauthorized($page);
    }
}
