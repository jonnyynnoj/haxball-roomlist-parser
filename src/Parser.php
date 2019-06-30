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
        $data = $response->getBody();

        $reader = $this->readerFactory->create($data, Endian::ENDIAN_BIG);
        $reader->readBytes(1);

        $rooms = [];

        while (!$reader->isEof()) {
            $rooms[] = $this->parseRoom($reader);
        }

        return $rooms;
    }

    private function parseRoom(Reader $reader)
    {
        $room = $this->modelFactory->createRoom()    
            ->setId($reader->readStringAuto());
              
        $reader->readUint16();
                
        $room->setVersion($reader->readUint8())
            ->setName($reader->readStringAuto())
            ->setCountry($reader->readStringAutoSingle())
            ->setLongitude($reader->readSingle())
            ->setLatitude($reader->readSingle())    
            ->setPassworded($reader->readUint8())
            ->setMaxPlayers($reader->readUint8())
            ->setPlayers($reader->readUint8());

        return $room;
    }

    private function sendRequest()
    {
        $referer = self::DOMAIN . 'index.html';
        $url = self::DOMAIN . 'rs/api/list';

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
