<?php
namespace App\Services;

use App\Extensions\CrestGuzzlerFactory;
use AllianceTools\User\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as CrestUser;
use AllianceTools\Character\Character;
use AllianceTools\Corporation\Corporation;
use AllianceTools\Service\Service;

class CharacterService
{
    /**
     * @var Socialite
     */
    private $socialite;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param Socialite $socialite
     * @param Guard $guard
     */
    public function __construct(Socialite $socialite, Guard $guard) {
        $this->socialite = $socialite;
        $this->guard = $guard;
    }

    /**
     * @param CrestUser $user
     * @return static
     */
    public function execute(CrestUser $user)
    {
        $character = Character::updateOrCreate(
            ['id' => $user->id],
            [
                'id' => $user->id,
                'name' => $user->name,
                'access_token' => $user->token,
                'refresh_token' => $user->user['RefreshToken'],
                'expires_on' => $user->user['ExpiresOn']
            ]
        );

        $corporation = Corporation::find(CrestGuzzlerFactory::makeFactory('character')
            ->getCharacter($user->id)->corporation->id);

        if (is_null($corporation) || is_null($corporation->alliance)
            || !in_array($corporation->alliance->id, [99006384, 99003393, 99004905, 99006109, 99005397])) {
            throw new BadRequestHttpException('Character not in valid corporation or alliance');
        }

        $character->corporation()->associate($corporation)->save();

        if (!$character->user instanceof User) {
            // create a new user
            if ($this->guard->check() === false) {
                $character->update(['primary' => true]);
                $user = User::create();
            } else {
                $user = $this->guard->user();
            }

            $character->user()->associate($user)->save();
        }

        if ($this->guard->check() === false) {
            $this->guard->login($character->user);
        }

        return $character->user;
    }
}