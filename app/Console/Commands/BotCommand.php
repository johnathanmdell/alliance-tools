<?php
namespace App\Console\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Console\Command;
use Predis\Async\Client;
use React\EventLoop\Factory;
use React\EventLoop\StreamSelectLoop;
use AllianceTools\Character\Character;
use AllianceTools\Service\Service;

class BotCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'bot:init';

    /**
     * @var string
     */
    protected $description = 'Instantiates the bot';

    /**
     * @var StreamSelectLoop
     */
    protected $loop;

    /**
     * @var Guild
     */
    protected $guild;

    public function handle()
    {
        $this->loop = Factory::create();

        $discord = new Discord([
            'token' => env('DISCORD_TOKEN'),
            'loop' => $this->loop
        ]);

        $discord->on('ready', function ($discord) {
            $this->guild = $discord->guilds->get('name', 'Black Sheep Coalition');

            // deal with private messages
            $discord->on('message', function ($message) {
                if ($message->channel->is_private) {
                    $this->authenticateCharacter($this->guild->members->get('id',
                        $message->author->id), $message);
                }
            });

            // display pings in the ping channel
            $redis = new Client(env('REDIS_HOST'), $this->loop);
            $redis->connect(function($redis) {
                $redis->pubSubLoop('bot:pings', function($event) {
                    $this->guild->channels->fetch(225236664438489089)
                        ->then(function($channel) use ($event) {
                            $channel->sendMessage($event->payload);
                        });
                });
            });
        });

        // attempt to send a heartbeat every 15 seconds
        $this->loop->addPeriodicTimer(15, function() use ($discord) {
            $discord->heartbeat();
        });

        $this->loop->run();
    }

    /**
     * @param Member $member
     * @param Message $message
     */
    private function authenticateCharacter(Member $member, Message $message)
    {
        $service = Service::find(1);
        $user = $service->users()->wherePivot('auth_key', $message->content)->first();
        $character = $user->characters()->primary()->first();

        if ($character instanceof Character) {
            $member->addRole('225231740967321611');
            $this->guild->members->save($member)->then(function () use ($member, $message, $character, $user, $service) {
                $member->setNickname($character->name . ' [' . $character->corporation->alliance->short_name . ']')
                    ->then(function () use ($member, $message, $user, $service) {
                    $message->reply('your character has been authenticated.');
                    if (!is_null($user->pivot->related_key)) {
                        $this->guild->members->fetch($user->pivot->related_key)->then(function($old_member) {
                            foreach ($old_member->roles as $role) {
                                $old_member->removeRole($role->id);
                            }
                            $this->guild->members->save($old_member);
                        });
                    }

                    $user->services()->updateExistingPivot($service->id, ['related_key' => $member->id]);
                });
            });
        }
    }
}