<?php

class SimpleTest extends \PHPUnit_Framework_TestCase {

    public function testResizeImagick() {
        //$resize = new DynImage\Filter\Resize(array('height' => 200, 'width' => 200));
        $resize = (new DynImage\Filter\Resize())->setHeight(200)->setWidth(200);

        $dynimage = new DynImage\DynImage();
        $dynimage->add($resize);

        $filename = __DIR__ . '/Fixtures/test.jpg';

        $image = $dynimage->apply(file_get_contents($filename), DynImage\Drivers::IMAGICK);

        $size = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
    }

    public function testResizeGd() {
        //$resize = new DynImage\Filter\Resize(array('height' => 200, 'width' => 200));
        $resize = (new DynImage\Filter\Resize())->setHeight(200)->setWidth(200);

        $dynimage = new DynImage\DynImage();
        $dynimage->add($resize);

        $filename = __DIR__ . '/Fixtures/test.jpg';

        $image = $dynimage->apply(file_get_contents($filename), DynImage\Drivers::GD);

        $size = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
    }

    public function testResizeGmagick() {
//        $resize = new DynImage\Filter\Resize(array('height' => 200, 'width' => 200));
        $resize = (new DynImage\Filter\Resize())->setHeight(200)->setWidth(200);


        $dynimage = new DynImage\DynImage();
        $dynimage->add($resize);

        $filename = __DIR__ . '/Fixtures/test.jpg';

        $image = $dynimage->apply(file_get_contents($filename), DynImage\Drivers::GMAGICK);

        $size = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
    }

}
