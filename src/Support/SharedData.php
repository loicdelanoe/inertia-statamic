<?php

namespace InertiaStatamic\InertiaStatamic\Support;

use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Nav;

class SharedData
{
    public static function all()
    {
        return [
            'navigations' => fn () => self::navigations(),
            'globals' => fn () => self::globals(),
            // ...
        ];
    }

    public static function navigations(): array
    {
        return Nav::all()
            ->mapWithKeys(fn ($nav) => [$nav->handle => self::resolveTree($nav->trees()->get('default')->tree())])
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

    protected static function resolveTree(array $tree): array
    {
        return collect($tree)->map(fn ($item) => self::resolveItem($item))->toArray();
    }

    protected static function resolveItem(array $item): array
    {
        if (isset($item['entry'])) {
            $entry = Entry::find($item['entry']);

            $item = array_merge($item, [
                'title' => $entry->title,
                // 'url' => $entry->slug === 'home' ? '/' : "/{$entry->slug}",
                'url' => self::resolveSlug($entry),
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

        if ($collectionName === 'pages') {
            return $entry->slug === 'home' ? '/' : "/{$entry->slug}";
        }

        return "/{$collectionName}/{$entry->slug}";
    }
}
