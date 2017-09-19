<?php

namespace RoomlistParser;

class ModelFactory
{
    public function createRoom()
    {
        return new Models\Room;
    }
}
