<?php
/**
 * Created by PhpStorm.
 * User: pascal
 * Date: 02/02/16
 * Time: 23:13
 */
require __DIR__.'/vendor/autoload.php';

$resize = (new DynImage\Filter\Resize())->setHeight(200)->setWidth(200);
$border = (new DynImage\Filter\Border())->setHeight(10)->setWidth(10);
$blur = (new DynImage\Filter\Blur())->setSigma(5);
$colorize = (new DynImage\Filter\Colorize())->setColor('ff9900');
$gamma = (new DynImage\Filter\Gamma())->setCorrection(1.5);
$reflect = (new DynImage\Filter\Reflect())->setColor('ff9900');
$crop = (new DynImage\Filter\Crop())->setX(10)->setY(10);


$dynimage = new DynImage\DynImage();
$dynimage->add((new DynImage\Filter\Resize())->setHeight(200)->setWidth(200));
$dynimage->add((new DynImage\Filter\Border())->setHeight(10)->setWidth(10));
$dynimage->add((new DynImage\Filter\Blur())->setSigma(5));
$dynimage->add((new DynImage\Filter\Colorize())->setColor('ff9900'));
$dynimage->add((new DynImage\Filter\Gamma())->setCorrection(1.5));
$dynimage->add((new DynImage\Filter\Reflect())->setColor('ff9900'));
$dynimage->add((new DynImage\Filter\Crop())->setX(10)->setY(10));

$filename = __DIR__ . '/tests/DynImage/Fixtures/test.jpg';

$image = $dynimage->apply(file_get_contents($filename), DynImage\Drivers::IMAGICK);

$image->save(__DIR__ . '/tests/DynImage/Fixtures/resize.jpg');