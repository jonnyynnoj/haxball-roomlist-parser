<?php

namespace RoomlistParser;

use RoomlistParser\Models\Room;
use PhpBinaryReader\Endian;

class Parser
{
    const URL = 'http://haxball.com/lis3';
    private $data;

    public function __construct()
    {
        $fh = @fopen(self::URL, 'r');

        if (!$fh) {
            throw new \Exception('Unable to open roomlist file');
        }

        $this->data = gzuncompress(stream_get_contents($fh));
    }

    public function parse()
    {
        $reader = new Reader($this->data, Endian::ENDIAN_BIG);
        $reader->readBytes(5);

        $rooms = [];

        while (!$reader->isEof()) {
            $reader->readBytes(2);
            $rooms[] = $this->parseRoom($reader);
        }

        return $rooms;
    }

    private function parseRoom(Reader $reader)
    {
        $room = (new Room)->setVersion($reader->readUint16())
            ->setId($reader->readStringAuto())
            ->setName($reader->readStringAuto())
            ->setPlayers($reader->readUint8())
            ->setMaxPlayers($reader->readUint8())
            ->setPassworded($reader->readUint8())
            ->setCountry($reader->readStringAuto())
            ->setLatitude($reader->readSingle())
            ->setLongitude($reader->readSingle());

        return $room;
    }
}
