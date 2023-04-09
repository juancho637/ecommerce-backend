<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @OA\Parameter(
     *     parameter="localization--lang",
     *     name="lang",
     *     description="Code of language",
     *     required=false,
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     * ),
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        App::setLocale($request->input('lang', App::getLocale()));

        return $next($request);
    }
}
