<?php
namespace App\Extensions\CrestGuzzler;

final class Fleet extends Resource
{
    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com/fleets/';

    /**
     * @param int $fleet_id
     * @return string
     */
    public function getFleet($fleet_id)
    {
        return $this->request('GET', $this->buildResourceUri($fleet_id));
    }

    /**
     * @param $fleet_id
     * @param array $fleetInformation
     * @return string
     */
    public function updateFleet($fleet_id, array $fleetInformation)
    {
        return $this->request('PUT', $this->buildResourceUri($fleet_id), $fleetInformation);
    }
}