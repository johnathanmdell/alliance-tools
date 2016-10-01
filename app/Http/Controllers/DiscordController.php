<?php
namespace App\Http\Controllers;

use AllianceTools\Service\Service;
use Illuminate\Contracts\Auth\Guard;

class DiscordController extends Controller
{
    /**
     * @Get("/discord", as="_discord_index")
     * @Middleware("auth")
     *
     * @param Guard $guard
     * @return View
     */
    public function index(Guard $guard)
    {
        $service = Service::find(1);
        $userService = $service->users()
            ->wherePivot('user_id', $guard->user()->id)->first();

        if (is_null($userService)) {
            $service->users()->attach($guard->user(), ['auth_key' => uniqid()]);
            $userService = $service->users()
                ->wherePivot('user_id', $guard->user()->id)->first();
        }

        return view('services.discord', compact('userService'));
    }
}
