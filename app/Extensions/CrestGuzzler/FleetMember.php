<?php
namespace App\Extensions\CrestGuzzler;

final class FleetMember extends Resource
{
    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com/fleets/%s/members/';

    /**
     * @return string
     */
    public function getFleetMembers()
    {
        return $this->request('GET', $this->buildResourceUri());
    }

    /**
     * @param int $character_id
     * @param string|null $role
     * @param int|null $position_id
     * @return string
     */
    public function addFleetMember($character_id, $role = null, $position_id = null)
    {
        $fleetMember = [
            'character' => [
                'href' => 'https://crest-tq.eveonline.com/characters/' . $character_id . '/'
            ],
            'role' => $role
        ];

        switch ($role) {
            case 'squadCommander':
                $fleetMember['squadID'] = $position_id;
                break;
            case 'wingCommander':
                $fleetMember['wingID'] = $position_id;
                break;
        }

        return $this->request('POST', $this->buildResourceUri(), $fleetMember);
    }

    /**
     * @param int $character_id
     * @param string|null $role
     * @param int|null $position_id
     * @return string
     */
    public function moveFleetMember($character_id, $role = null, $position_id = null)
    {
        $fleetMember = ['newRole' => $role];

        switch ($role) {
            case 'squadCommander':
                $fleetMember['newSquadID'] = $position_id;
                break;
            case 'wingCommander':
                $fleetMember['newWingID'] = $position_id;
                break;
        }

        return $this->request('PUT', $this->buildResourceUri($character_id), $fleetMember);
    }

    /**
     * @param int $character_id
     * @return string
     */
    public function removeFleetMember($character_id)
    {
        return $this->request('DELETE', $this->buildResourceUri($character_id));
    }
}