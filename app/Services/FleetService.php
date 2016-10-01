<?php
namespace App\Services;

use App\Extensions\CrestGuzzlerFactory;
use AllianceTools\Character\Character;
use AllianceTools\Fleet\FleetInterface;
use AllianceTools\Member\MemberInterface;
use Illuminate\Contracts\Auth\Guard;

class FleetService
{
    /**
     * @var FleetInterface
     */
    private $fleetModel;

    /**
     * @var MemberInterface
     */
    private $memberModel;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param FleetInterface $fleet
     * @param MemberInterface $member
     * @param Guard $guard
     */
    public function __construct(
        FleetInterface $fleet,
        MemberInterface $member,
        Guard $guard
    ) {
        $this->fleetModel = $fleet;
        $this->memberModel = $member;
        $this->guard = $guard;
    }

    /**
     * @param integer $fleet_id
     * @return boolean
     */
    public function createFleet($fleet_id)
    {
        $fleetInformation = $this->getFleetInformation($fleet_id);

        if ($fleetInformation !== false) {
            // Create the fleet
            $fleet = $this->fleetModel->create([
                'id' => $fleet_id
            ]);

            // Associate the user with this fleet
            $fleet->user()->associate($this->guard->user());

            foreach ($this->getFleetMembers($fleet_id)->items as $fleetMember) {
                // Find the character or create it
                $character = Character::firstOrCreate([
                    'id' => $fleetMember->character->id,
                    'name' => $fleetMember->character->name
                ]);

                // Create a fleet member based on the character
                $member = $this->memberModel->create([
                    'ship' => $fleetMember->ship->name,
                    'location' => $fleetMember->solarSystem->name,
                    'joined_at' => $fleetMember->joinTime
                ]);

                $member->fleet()->associate($fleet);
                $member->character()->associate($character);
                $member->save();
            }

            // Update the Fleet MOTD
            $this->setFleetMotd($fleet_id, $fleetInformation->motd);

            // Save the fleet
            $fleet->save();

            return $fleet;
        }

        return false;
    }

    /**
     * @param integer $fleet_id
     * @return mixed
     */
    private function getFleetInformation($fleet_id)
    {
        $crestGuzzler = CrestGuzzlerFactory::makeFactory('fleet');
        $crestGuzzler->setUser($this->guard->user());

        return $crestGuzzler->getFleet($fleet_id);
    }

    /**
     * @param integer $fleet_id
     * @param string $motd
     * @return mixed
     */
    private function setFleetMotd($fleet_id, $motd)
    {
        $crestGuzzler = CrestGuzzlerFactory::makeFactory('fleet');
        $crestGuzzler->setUser($this->guard->user());

        return $crestGuzzler->updateFleet($fleet_id, array('motd' => $motd . '<br><br><b><color=0xffffffff>Fleet tracking has been enabled.</color></b>'));
    }

    /**
     * @param integer $fleet_id
     * @return mixed
     */
    private function getFleetMembers($fleet_id)
    {
        $crestGuzzler = CrestGuzzlerFactory::makeFactory('fleetMember', $fleet_id);
        $crestGuzzler->setUser($this->guard->user());

        return $crestGuzzler->getFleetMembers();
    }
}