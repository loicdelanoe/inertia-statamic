<?php

namespace InertiaStatamic\InertiaStatamic\Support;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Nav;

class SharedData
{
    public static function all(): array
    {
        return array_merge([
            'csrf' => fn() => self::csrf(),
            'navigations' => fn() => self::navigations(),
            'globals' => fn() => self::globals(),
            'old' => fn() => self::old(),
            'fullPath' => fn() => request()->fullUrl(),
            'locale' => fn() => self::locale(),
            'pageLocale' => fn() => self::pageLocale(),
            // ...
        ]);
    }

    protected static function navigations(): array
    {
        // dd(Nav::all()->first()->trees()->get('default')->tree());

        return Nav::all()
            ->mapWithKeys(fn($nav) => [$nav->handle => self::resolveTree($nav->trees()->get('default')->tree())])
            ->toArray();
    }

    protected static function globals(): array
    {
        $globals = [];

        foreach (GlobalSet::all() as $globalSet) {
            $handle = $globalSet->handle();
            $localized = $globalSet->in('default');
            $globals[$handle] = $localized ? $localized->toAugmentedArray() : [];
        }

        return $globals;
    }

    protected static function old(): array
    {
        return session()->getOldInput();
    }

    protected static function locale(): string
    {
        return str_replace('_', '-', app()->getLocale());
    }

    protected static function csrf(): string
    {
        if (! app()->bound('session')) {
            return '';
        }

        return csrf_token();
    }

    protected static function pageLocale(): string
    {
        $path = request()->path();

        if ($path === '' || $path === '/') {
            $path = '/';
        } else {
            $path = Str::start($path, '/');
        }

        $page = Entry::findByUri($path);

        if (! $page || ! $page->lang) {
            return app()->getLocale();
        }

        return $page->lang;
    }

    protected static function resolveTree(array $tree): array
    {
        return collect($tree)->map(fn($item) => self::resolveItem($item))->toArray();
    }

    protected static function resolveItem(array $item): array
    {
        if (isset($item['entry'])) {
            $entry = Entry::find($item['entry']);


            $item = array_merge($item, [
                'title' => $entry->title,
                'url' => $entry->url(),
            ]);

            unset($item['entry']);
        }

        if (! empty($item['children'])) {
            $item['children'] = self::resolveTree($item['children']);
        }

        return $item;
    }

    protected static function resolveSlug($entry): string
    {
        $collectionName = strtolower($entry->collection->handle);

        if ($collectionName === 'page') {
            return $entry->slug === 'home' ? '/' : "/{$entry->slug}";
        }

        return "/{$collectionName}/{$entry->slug}";
    }
}
