<?php

namespace App\Providers;

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
        $this->app->bind( Generator::class, function ( $app ) {

            $faker = \Faker\Factory::create();
            $faker->addProvider( new CustomFakerProvider( $faker ) );

            return $faker;
        } );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'book' => 'App\Book',
            'magazine' => 'App\Magazine',
        ]);
//??home user softDel
        Route::bind('trashed_user', function($id) {
            return User::withTrashed()->findOrFail($id);
        })
    }
}
