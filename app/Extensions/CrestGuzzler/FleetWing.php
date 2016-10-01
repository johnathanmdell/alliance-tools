<?php
namespace App\Extensions\CrestGuzzler;

final class FleetWing extends Resource
{
    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com/fleets/%s/wings/';

    /**
     * @return string
     */
    public function getFleetWings()
    {
        return $this->request('GET', $this->buildResourceUri());
    }

    /**
     * @return string
     */
    public function addFleetWing()
    {
        return $this->request('POST', $this->buildResourceUri());
    }

    /**
     * @param int $wing_id
     * @param $name
     * @return string
     */
    public function renameFleetWing($wing_id, $name)
    {
        return $this->request('PUT', $this->buildResourceUri($wing_id), ['name' => $name]);
    }

    /**
     * @param int $wing_id
     * @return string
     */
    public function deleteFleetWing($wing_id)
    {
        return $this->request('DELETE', $this->buildResourceUri($wing_id));
    }
}