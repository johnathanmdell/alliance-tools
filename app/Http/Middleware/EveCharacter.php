<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class EveCharacter
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check() === false && !in_array($request->route()->getName(), [
                '_get_remember',
                '_post_remember'
            ])) {
            if (isset($_SERVER['HTTP_EVE_CHARID']) === true) {
                return redirect()->route('_get_remember');
            }
        }

        return $next($request);
    }
}
