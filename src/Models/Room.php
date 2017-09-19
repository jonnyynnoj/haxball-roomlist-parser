<?php

namespace RoomlistParser\Models;

class Room implements \JsonSerializable
{
    /** @var int */
    private $version;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $players;

    /** @var int */
    private $maxPlayers;

    /** @var bool */
    private $passworded;

    /** @var string */
    private $country;

    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    public function jsonSerialize()
    {
        return [
            'version' => $this->version,
            'id' => $this->id,
            'name' => $this->name,
            'players' => $this->players,
            'maxPlayers' => $this->maxPlayers,
            'passworded' => $this->passworded,
            'country' => $this->country,
            'lat' => $this->lat,
            'lon' => $this->lon
        ];
    }

    public function setVersion($version)
    {
        $this->version = (int) $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setId($id)
    {
        $this->id = (string) $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPlayers($players)
    {
        $this->players = (int) $players;
        return $this;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function setMaxPlayers($maxPlayers)
    {
        $this->maxPlayers = (int) $maxPlayers;
        return $this;
    }

    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }

    public function setPassworded($passworded)
    {
        $this->passworded = (bool) $passworded;
        return $this;
    }

    public function isPassworded()
    {
        return $this->passworded;
    }

    public function setCountry($country)
    {
        $this->country = (string) $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setLatitude($lat)
    {
        $this->lat = (float) $lat;
        return $this;
    }

    public function getLatitude()
    {
        return $this->lat;
    }

    public function setLongitude($lon)
    {
        $this->lon = (float) $lon;
        return $this;
    }

    public function getLongitude()
    {
        return $this->lon;
    }
}
