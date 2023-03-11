<?php

namespace App\Providers;

use App\Repositories\Eloquent\CastMemberEloquentRepository;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Repositories\CastMemberRepository;
use Core\Domain\Repositories\CategoryRepository;
use Core\Domain\Repositories\GenreRepository;
use Core\Shared\Repository\Transaction;
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
        $this->app->singleton(
            CategoryRepository::class,
            CategoryEloquentRepository::class
        );
        $this->app->singleton(
            GenreRepository::class,
            GenreEloquentRepository::class
        );

        $this->app->singleton(
            CastMemberRepository::class,
            CastMemberEloquentRepository::class
        );

        $this->app->bind(
            Transaction::class,
            DBTransaction::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
