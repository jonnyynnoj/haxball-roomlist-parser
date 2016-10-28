# Haxball Roomlist Parser

Parses the [haxball](http://haxball.com) roomlist

```
composer require jonnyynnoj/haxball-roomlist-parser
```

## Usage

```php
$parser = new RoomlistParser\Parser();
$rooms = $parser->parse();

foreach ($rooms as $room) {
    echo $room->getName();
}
```

Output as JSON:

```php
echo json_encode($rooms);
```
