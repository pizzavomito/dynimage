# DynImage

[![Build Status](https://travis-ci.org/pizzavomito/dynimage.png)](https://travis-ci.org/pizzavomito/dynimage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd/mini.png)](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd)

## Basic Usage Example

```php

$dynimage = new DynImage\DynImage();
$dynimage->add((new DynImage\Filter\Resize())->setHeight(200)->setWidth(200));
$dynimage->add((new DynImage\Filter\Border())->setHeight(10)->setWidth(10));
$dynimage->add((new DynImage\Filter\Blur())->setSigma(5));
$dynimage->add((new DynImage\Filter\Colorize())->setColor('ff9900'));
$dynimage->add((new DynImage\Filter\Gamma())->setCorrection(1.5));
$dynimage->add((new DynImage\Filter\Reflect())->setColor('ff9900'));
$dynimage->add((new DynImage\Filter\Crop())->setX(10)->setY(10));

$image = $dynimage->apply(file_get_contents('/path/to/image'));

$image->show('png');

$image->save('/path/to/image.png');
```
## Filter Application Order

Filters listen to events of dynimage to apply at the right time. 
Filters that are connected to the same event are applied in the order they were added to Dynimage.

Events are :
```php
  AFTER_CREATE_IMAGE
  EARLY_APPLY_FILTER
  LATE_APPLY_FILTER
  FINISH_CREATE_IMAGE
```

However, you can change the event of a filter like this.
```php
use DynImage\Events;
use DynImage\Filter\Rotate;

$rotate = (new Rotate())->setAngle(45)->setEvent(Events::FINISH_CREATE_IMAGE);

$dynimage = new DynImage\DynImage();
$dynimage->add($rotate);
or
$dynimage = new DynImage\DynImage();
$dynimage->add((new Rotate())->setAngle(45), Events::FINISH_CREATE_IMAGE);

```
##License

MIT License
