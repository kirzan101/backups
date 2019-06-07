<?php

namespace App\Providers;

use Auth;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('is_correct_password', function ($attribute, $value, $parameters, $validator) {
            $current_pass = Auth::user()->password;

            if (!empty($value) && Hash::check($value, $current_pass)) {
                return true;
            }
            return false;
        });

        Validator::extend('check_out', function ($atrribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $check_in = new DateTime(date('Y-m-d H:i:s', strtotime($data['check_in_date'] . ' ' . $data['check_in_time'])));
            $check_out = new DateTime(date('Y-m-d H:i:s', strtotime($data['check_out_date'] . ' ' . $data['check_out_time'])));

            $interval = $check_in->diff($check_out);
            $hour_diff = $interval->format('%h');

            return ($hour_diff >= 12 && $interval->invert == 0) || $interval->days > 0;
        });

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
