<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Locale;

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
        $browserLanguage = $this->matchAcceptLanguage($request->server('HTTP_ACCEPT_LANGUAGE'), ['nl', 'fr', 'en']);

        if (!empty(Input::get('lang'))) {
            // if a language is set in the browser, we need to update the language. User may have requested a switch.
            $language = Input::get('lang');
        } elseif (empty(Session::get('lang')) && !empty($browserLanguage)) {
            // if the user didn't set a language in the cookie, and a browser language is available, use browser language.
            $language = $browserLanguage;
        } else {
            $language = Session::get('lang');
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

    /**
     * Search a HTTP accept-header for a supported language. Take order (weight, q=) into account. Skip unsupported
     * languages.
     *
     * This method has a big avantage over locale_lookup, as locale_lookup will only look at the first language in accept-language.
     * This method will skip unsupported languages and keep searching for a supported, meaning that if only NL and EN are supported,
     * da, nl;q=0.5, en;q=0.2 will result in nl being chosen. (vs null return from locale_lookup)
     *
     * @param string $header the HTTP accept-language header to search
     * @param array  $supported the list of supported languages
     * @return string|null if a language matches, a value from the supported array is returned. If not, null is
     *     returned.
     */
    private function matchAcceptLanguage($header, $supported)
    {

        // step 1: transform header into array
        // source for step 1: http://stackoverflow.com/questions/6168519/transform-accept-language-output-in-array

        $values = explode(',', $header);

        $accept_language = array();

        foreach ($values AS $lang) {
            $cnt = preg_match('/([-a-zA-Z]+)\s*;\s*q=([0-9\.]+)/', $lang, $matches);
            if ($cnt === 0) {
                $accept_language[$lang] = 1;
            } else {
                $accept_language[$matches[1]] = $matches[2];
            }
        }

        // step 2: match array with supported languages

        foreach ($accept_language as $accept_lang => $accept_lang_q) {
            foreach ($supported as $supported_lang) {
                // use filterMatches to ensure nl-be, nl, nl-nl all match nl, etc..
                if (Locale::filterMatches($accept_lang, $supported_lang)) {
                    return $supported_lang;
                };
            }
        }

        // no match found
        return null;
    }
}
