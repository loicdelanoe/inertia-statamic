<?php

namespace InertiaStatamic\InertiaStatamic\Support;

use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Nav;
use Statamic\Facades\Site;

class SharedData
{
    public static function all(): array
    {
        return array_merge([
            'csrf' => fn () => self::csrf(),
            'navigations' => fn () => self::navigations(),
            'globals' => fn () => self::globals(),
            'old' => fn () => self::old(),
            'fullPath' => fn () => request()->fullUrl(),
            'locale' => fn () => self::locale(),
            'pageLocale' => fn () => self::pageLocale(),
            'editUrl' => fn () => self::editUrl(),
            'published' => fn () => self::published(),
            // ...
        ], Multilingual::enabled() ? ['relatedTranslations' => fn () => self::relatedTranslations()] : []);
    }

    protected static function navigations(): array
    {
        return Nav::all()->mapWithKeys(function ($nav) {
            $tree = $nav->trees()->get(Site::current()->handle())->tree();

            $entryIds = collect($tree)->pluck('entry')->toArray();

            $entries = Entry::whereInId($entryIds);

            $navItem = [];

            foreach ($entries as $entry) {
                $navItem[] = [
                    'id' => $entry->id(),
                    'title' => $entry->title,
                    'url' => $entry->url(),
                ];
            }

            return [$nav->handle => $navItem];
        })->toArray();
    }

    protected static function globals(): array
    {
        $globals = [];

        foreach (GlobalSet::all() as $globalSet) {
            $handle = $globalSet->handle();
            $localized = $globalSet->in('default');
            $globals[$handle] = $localized ? $localized->data()->all() : [];
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
        $page = request()->attributes->get('page');

        if (! $page || ! $page->lang) {
            return app()->getLocale();
        }

        return $page->lang;
    }

    protected static function editUrl(): ?string
    {
        $page = request()->attributes->get('page');

        return $page ? $page->edit_url : null;
    }

    protected static function published(): ?bool
    {
        $page = request()->attributes->get('page');

        return $page ? $page->published : null;
    }

    protected static function relatedTranslations(): array
    {
        $page = request()->attributes->get('page');

        if (! $page || ! $page->related_translations) {
            return [];
        }

        return collect($page->related_translations)->map(function ($translation) {
            if (
                ! isset($translation->language['value']) ||
                ! isset($translation->entry['permalink'])
            ) {
                return null;
            }

            return [
                'lang' => $translation->language['value'],
                'url' => $translation->entry['permalink'],
            ];
        })->filter()->values()->toArray();
    }
}
