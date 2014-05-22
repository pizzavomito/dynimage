# DynImage

[![Build Status](https://travis-ci.org/pizzavomito/dynimage.png)](https://travis-ci.org/pizzavomito/dynimage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd/mini.png)](https://insight.sensiolabs.com/projects/b79e32da-ab28-4697-b0b6-f3c5b13c07cd)
[![Stillmaintained](http://stillmaintained.com/pizzavomito/dynimage.png)](http://stillmaintained.com/pizzavomito/dynimage)

## Basic Usage Example

```php
use DynImage\Filter\Resize;
use DynImage\Filter\Border;
use DynImage\Filter\Colorize;
use DynImage\Filter\Reflect;
use DynImage\DynImage;

$dynimage = new DynImage();
$dynimage->add(new Resize(array('height' => 200, 'width' => 200)));
$dynimage->add(new Colorize(array('color' => '#ff9900')));
$dynimage->add(new Border(array('height' => 6, 'width' => 6, 'color', '#000')));
$dynimage->add(new Reflect());

$content = file_get_contents('/path/to/image');

$image = $dynimage->apply($content);

$image->show('png');

$image->save('/path/to/image.png');
```
## Filter Application Order

Filters listen to events of dynimage to apply at the right time. 
Filters that are connected to the same event are applied in the order they were added to transformer.

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

$rotate = new Rotate(array('angle' => 45));
$rotate->setEvent(Events::FINISH_CREATE_IMAGE);

$dynimage = new DynImage();
$dynimage->add($rotate);
or
$dynimage = new DynImage();
$dynimage->add(new Rotate(array('angle' => 45)), Events::FINISH_CREATE_IMAGE);

```
##License

MIT License
