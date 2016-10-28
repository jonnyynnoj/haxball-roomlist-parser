<?php

namespace RoomlistParser;

use PhpBinaryReader\BinaryReader;

class Reader extends BinaryReader
{
    public function readStringAuto()
    {
        $len =  $this->readUInt16();
        return $len > 0 ? $this->readString($len) : '';
    }
}
