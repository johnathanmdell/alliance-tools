<?php
namespace App\Extensions\CrestGuzzler;

final class FleetWingSquad extends Resource
{
    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com/fleets/%s/wings/%s/squads/';

    /**
     * @return string
     */
    public function getFleetWingSquads()
    {
        return $this->request('GET', $this->buildResourceUri());
    }

    /**
     * @return string
     */
    public function addFleetWingSquad()
    {
        return $this->request('POST', $this->buildResourceUri());
    }

    /**
     * @param int $squad_id
     * @param string $name
     * @return string
     */
    public function renameFleetWingSquad($squad_id, $name)
    {
        return $this->request('PUT', $this->buildResourceUri($squad_id), ['name' => $name]);
    }

    /**
     * @param int $squad_id
     * @return string
     */
    public function deleteFleetWingSquad($squad_id)
    {
        return $this->request('DELETE', $this->buildResourceUri($squad_id));
    }
}