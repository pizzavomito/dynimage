<?php

class SimpleTest extends \PHPUnit_Framework_TestCase {
    
    public function testResizeImagick()
    {
        $resize = new DynImage\Filter\Resize\Resize(array('height' => 200, 'width' => 200));
        
        $transformer = new DynImage\Transformer();
        $transformer->add($resize);
        
        $filename = __DIR__.'/Fixtures/test.jpg';
        
        $image = DynImage\DynImage::getImage($transformer,file_get_contents($filename),$filename,'Imagick');
        
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
        
        
    }
    
    public function testResizeGd()
    {
        $resize = new DynImage\Filter\Resize\Resize(array('height' => 200, 'width' => 200));
        
        $transformer = new DynImage\Transformer();
        $transformer->add($resize);
        
        $filename = __DIR__.'/Fixtures/test.jpg';
        
        $image = DynImage\DynImage::getImage($transformer,file_get_contents($filename),$filename,'Gd');
        
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
        
        
    }
    
     public function testResizeGmagick()
    {
        $resize = new DynImage\Filter\Resize\Resize(array('height' => 200, 'width' => 200));
        
        $transformer = new DynImage\Transformer();
        $transformer->add($resize);
        
        $filename = __DIR__.'/Fixtures/test.jpg';
        
        $image = DynImage\DynImage::getImage($transformer,file_get_contents($filename),$filename,'Gmagick');
        
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(200, $size->getWidth());
        $this->assertEquals(200, $size->getHeight());
        
        
    }
}

