# DynImage

[![Build Status](https://travis-ci.org/pizzavomito/dynimage.png)](https://travis-ci.org/pizzavomito/dynimage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd/mini.png)](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd)

## Basic Usage Example

```php
use DynImage\Filter\Resize\Resize;
use DynImage\Filter\Border\Border;
use DynImage\Filter\Colorize\Colorize;
use DynImage\Filter\Reflect\Reflect;
use DynImage\DynImage;
use DynImage\Events;

$resize = new Resize(array('height' => 200, 'width' => 200));
$color = new Colorize(array('color' => '#ff9900'));
$border = new Border(array('height' => 6, 'width' => 6, 'color', '#000'));
$reflect = new Reflect();

$transformer = new Transformer();
$transformer->add($resize);
$transformer->add($color);
$transformer->add($border);
$transformer->add($reflect);

$filename = 'path/to/image';

DynImage::getImage($transformer, file_get_contents($filename), $filename)->show('png');
```
## Events

Filters listen to events of dynimage to apply at the right time. 
Filters that are connected to the same event are applied in the order they were added to transformer.

Events are :
```php
  BEFORE_CREATE_IMAGE
  AFTER_CREATE_IMAGE
  EARLY_APPLY_FILTER
  LATE_APPLY_FILTER
  FINISH_CREATE_IMAGE
```

You can change the event filter.
```php
$rotate = new Rotate(array('angle' => 45));
$rotate->setEvent(Events::FINISH_CREATE_IMAGE);
```
##License

MIT License
