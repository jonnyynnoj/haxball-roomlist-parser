<?php

namespace RoomlistParser;

use PhpBinaryReader\Endian;

class ReaderFactory
{
    public function create($input, $endian = Endian::ENDIAN_LITTLE)
    {
        return new Reader($input, $endian);
    }
}
