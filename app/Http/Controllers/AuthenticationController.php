<?php
namespace App\Http\Controllers;

use AllianceTools\User\User;
use App\Http\Requests\PasswordCreateRequest;
use App\Services\CharacterService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthenticationController extends Controller
{
    /**
     * @Get("/auth/login", as="_auth_login")
     * @Middleware("guest")
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * @Get("/auth/logout", as="_auth_logout")
     * @Middleware("web")
     *
     * @param Guard $guard
     * @return RedirectResponse
     */
    public function getLogout(Guard $guard)
    {
        $guard->logout();

        return redirect()->route('_get_login');
    }

    /**
     * @Get("/auth/password", as="_auth_get_password")
     * @Middleware("password")
     */
    public function getPassword()
    {
        return view('auth.password');
    }

    /**
     * @Post("/auth/password", as="_auth_post_password")
     * @Middleware("password")
     *
     * @param Guard $guard
     * @param PasswordCreateRequest $passwordCreateRequest
     * @return RedirectResponse
     */
    public function postPassword(Guard $guard, PasswordCreateRequest $passwordCreateRequest)
    {
        User::find($guard->user()->getAuthIdentifier())
            ->update(['password' => bcrypt($passwordCreateRequest->input('password')),
                'email_address' => $passwordCreateRequest->input('email_address')]);

        return redirect()->route('_index');
    }

    /**
     * @Get("/auth/retry", as="_auth_retry")
     * @Middleware("web")
     */
    public function getRetry()
    {
        return view('auth.retry');
    }

    /**
     * @Get("/auth/redirect", as="_auth_redirect")
     * @Middleware("web")
     */
    public function getRedirect()
    {
        return Socialite::driver('crest')->scopes([
            'publicData'
        ])->redirect();
    }

    /**
     * @Get("/auth/callback", as="_auth_callback")
     * @Middleware("web")
     *
     * @param CharacterService $characterService
     * @return RedirectResponse
     */
    public function getCallback(CharacterService $characterService)
    {
        try {
            if ($characterService->execute(Socialite::driver('crest')->user()) instanceof User) {
                return redirect()->route('_index');
            }
        } catch (InvalidStateException $e) {
            return redirect()->route('_get_retry');
        }

        return redirect()->route('_get_redirect');
    }
}