<?php

use Illuminate\Support\Facades\Route;
use InertiaStatamic\InertiaStatamic\Http\Controllers\FormHandle;

Route::prefix(config('inertia-statamic.route_prefix'))
    ->middleware('web')
    ->post('/form/{handle}', [FormHandle::class, 'handle']);
