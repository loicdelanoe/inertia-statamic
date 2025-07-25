<?php

namespace InertiaStatamic\InertiaStatamic\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use JsonSerializable;
use Statamic\Entries\Entry;
use Statamic\Facades\Data;
use Statamic\Fields\Value;
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
        $queryString = $request->getRequestUri() ? str_replace('?'.$request->getQueryString(), '', $request->getRequestUri()) : '/index';

        $page = Data::findByUri($queryString);

        if (! ($page instanceof Page || $page instanceof Entry)) {
            return $next($request);
        }

        return Inertia::render(
            $this->buildComponentPath($page),
            ['content' => $this->buildProps($page)]
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

    /**
     * Convert the Statamic object into props.
     *
     * @return array|Carbon|mixed
     */
    protected function buildProps($data)
    {
        if ($data instanceof Carbon) {
            return $data;
        }

        if ($data instanceof JsonSerializable || $data instanceof Collection) {
            return $this->buildProps($data->jsonSerialize());
        }

        if (is_array($data)) {
            return collect($data)->map(function ($value) {
                return $this->buildProps($value);
            })->all();
        }

        if ($data instanceof Value) {
            return $data->value();
        }

        if (is_object($data) && method_exists($data, 'toAugmentedArray')) {
            return $this->buildProps($data->toAugmentedArray());
        }

        if (gettype($data) === 'string' && Str::isUuid($data)) {
            $data = Data::find($data);
        }

        return $data;
    }
}
