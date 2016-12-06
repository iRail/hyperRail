<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class Language
{
    /**
     * @var array
     */
    protected static $supportedLanguages = [
        'nl',
        'en',
        'fr',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $browserLanguage = \Locale::lookup(['nl', 'fr', 'en'], $request->server('HTTP_ACCEPT_LANGUAGE'));

        // if the user didn't set a language in the cookie, and a browser language is available, use browser language.
        if (empty(Session::get('lang') && !empty($browserLanguage))) {
            $language = $browserLanguage;
        } else {
            $language = (Input::get('lang')) ?: Session::get('lang');
        }

        $this->setSupportedLanguage($language);
        return $next($request);
    }

    /**
     * @param string $lang
     *
     * @return bool
     */
    private function isLanguageSupported($lang)
    {
        return in_array($lang, self::$supportedLanguages);
    }

    /**
     * @param string $lang
     */
    private function setSupportedLanguage($lang)
    {
        if ($this->isLanguageSupported($lang)) {
            App::setLocale($lang);
            Session::put('lang', $lang);
        }
    }
}
