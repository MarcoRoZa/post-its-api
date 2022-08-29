<?php

namespace App\Providers;

use App\Models\Note;
use App\Observers\NoteObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        Note::observe(NoteObserver::class);

        if (!$this->app->isLocal()) URL::forceScheme('https');
    }
}
