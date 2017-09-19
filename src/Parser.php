<?php

namespace RoomlistParser;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use RoomlistParser\Models\Room;
use PhpBinaryReader\Endian;

class Parser
{
    const DOMAIN = 'http://www.haxball.com/';

    /** @var ClientInterface */
    private $client;

    /** @var ReaderFactory */
    private $readerFactory;

    /** @var ModelFactory */
    private $modelFactory;

    public function __construct(
        ClientInterface $client,
        ReaderFactory $readerFactory,
        ModelFactory $modelFactory
    ) {
        $this->client = $client;
        $this->readerFactory = $readerFactory;
        $this->modelFactory = $modelFactory;
    }

    public static function create()
    {
        return new self(
            new \GuzzleHttp\Client,
            new ReaderFactory,
            new ModelFactory
        );
    }

    public function parse()
    {
        $response = $this->sendRequest();
        $data = gzuncompress($response->getBody());

        $reader = $this->readerFactory->create($data, Endian::ENDIAN_BIG);
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
        $room = $this->modelFactory->createRoom()
            ->setVersion($reader->readUint16())
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

    private function sendRequest()
    {
        $referer = self::DOMAIN . 'index.html';
        $url = self::DOMAIN . 'list3';

        $options = [
            'headers' => [
                'Referer' => $referer
            ]
        ];
        
        try {
            return $this->client->request('GET', $url, $options);
        } catch (RequestException $e) {
            throw new \Exception('Unable to open roomlist file', null, $e);
        }
    }
}
