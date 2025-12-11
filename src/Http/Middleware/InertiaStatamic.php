<?php

namespace InertiaStatamic\InertiaStatamic\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $queryString = $request->getRequestUri() ? str_replace('?' . $request->getQueryString(), '', $request->getRequestUri()) : '/index';

        $page = Entry::findByUri($queryString);

        if (! ($page instanceof Page || $page instanceof Entry)) {
            return $next($request);
        }

        return Inertia::render(
            $this->buildComponentPath($page),
            ['content' => $page->toAugmentedArray()]
        );
    }

    /**
     * Build the path for the component based on Pages Blueprint Name
     */
    protected function buildComponentPath($data): string
    {
        $values = $data->toAugmentedArray();

        return $values['blueprint']->raw()->contents()['title'];
    }
}
