<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If on production, redirect to a secure page if possible
        // TODO: set up nginx to do this automatically
        if (App::environment() == 'production') {
            if (!Request::secure()) {
                return Redirect::secure(Request::getRequestUri());
            }
        }
        // Language negotiation
        if (Input::get('lang')) {
            $languages = array('nl', 'en', 'fr');
            $locale = Input::get('lang');
            if (in_array($locale, $languages)) {
                App::setLocale($locale);
                Session::put('lang', $locale);
            } else {
                $locale = null;
            }
        } else {
            $languages = array('nl', 'en', 'fr');
            $locale = Session::get('lang');
            if (in_array($locale, $languages)) {
                App::setLocale($locale);
            } else {
                $locale = null;
            }
        }

        return $next($request);
    }
}
