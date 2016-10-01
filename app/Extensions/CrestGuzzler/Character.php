<?php
namespace App\Extensions\CrestGuzzler;

final class Character extends Resource
{
    /**
     * @var string
     */
    protected $base_uri = 'https://crest-tq.eveonline.com/characters/';

    /**
     * @param int $character_id
     * @return string
     */
    public function getCharacter($character_id)
    {
        return $this->request('GET', $this->buildResourceUri($character_id));
    }
}