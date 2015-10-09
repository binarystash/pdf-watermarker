<?php

$parent_directory = dirname(__FILE__);

require_once($parent_directory."/../vendor/binarystash/fpdf/fpdf.php");
require_once($parent_directory."/../vendor/setasign/fpdi/fpdi.php");
require_once($parent_directory."/../pdfwatermarker/pdfwatermark.php");

class PDFWatermark_test extends PHPUnit_Framework_TestCase
{
    public $watermark;
    public $output;
	public $parent_directory;

    function setUp() {
		
		$this->parent_directory = dirname(__FILE__);
		
        $this->watermark = new PDFWatermark($this->parent_directory.'/../assets/star.png');

    }
	
    public function testSetPosition() {
		$this->watermark->setPosition('bottomleft');
		$this->assertTrue( $this->watermark->getPosition() == 'bottomleft' );
    }
	
	public function testSetAsBackground() {
		$this->watermark->setAsBackground();
		$this->assertTrue( $this->watermark->usedAsBackground() === true );
	}
	
	public function testSetAsOverlay() {
		$this->watermark->setAsBackground();
		$this->watermark->setAsOverlay();
		$this->assertTrue( $this->watermark->usedAsBackground() === false );
	}
	
	public function testGetFilePath() {
		$this->assertTrue( file_exists($this->watermark->getFilePath()) === true );
	}
	
	public function testGetHeight() {
		$this->assertTrue( $this->watermark->getHeight() == 200 );
	}
	
	public function testGetWidth() {
		$this->assertTrue( $this->watermark->getWidth()== 200 );
	}

	public function testPrepareImagePng() {
		$class = new ReflectionClass('PDFWatermark');
		$method = $class->getMethod('_prepareImage');
		$method->setAccessible(true);

  		$fileExtension = substr($method->invokeArgs($this->watermark, [__DIR__ . '/test.png']), -4);

  		$this->assertSame('.png', $fileExtension);
	}

	public function testPrepareImageJpg() {
		$class = new ReflectionClass('PDFWatermark');
		$method = $class->getMethod('_prepareImage');
		$method->setAccessible(true);

  		$fileExtension = substr($method->invokeArgs($this->watermark, [__DIR__ . '/test.jpg']), -4);

  		$this->assertSame('.jpg', $fileExtension);
	}

	/**
     * @expectedException Exception
     * @expectedExceptionMessage Unsupported image type
     */
	public function testPrepareImageInvalidImage() {
		$class = new ReflectionClass('PDFWatermark');
		$method = $class->getMethod('_prepareImage');
		$method->setAccessible(true);

  		$fileExtension = $method->invokeArgs($this->watermark, [__DIR__ . '/test.tiff']);
	}
	
}