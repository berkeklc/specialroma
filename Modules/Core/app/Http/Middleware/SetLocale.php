<?php

declare(strict_types=1);

namespace Modules\Core\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $activeLanguages = config('core.active_languages', ['tr', 'en']);
        $defaultLanguage = config('core.default_language', 'tr');

        $locale = Session::get('locale')
            ?? $this->detectFromBrowser($request, $activeLanguages)
            ?? $defaultLanguage;

        if (! in_array($locale, $activeLanguages, true)) {
            $locale = $defaultLanguage;
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }

    /** @param list<string> $activeLanguages */
    private function detectFromBrowser(Request $request, array $activeLanguages): ?string
    {
        $browserLanguages = $request->getLanguages();

        foreach ($browserLanguages as $lang) {
            $code = substr($lang, 0, 2);
            if (in_array($code, $activeLanguages, true)) {
                return $code;
            }
        }

        return null;
    }
}
