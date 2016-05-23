<?php

class PDFWatermarker_test extends PHPUnit_Framework_TestCase
{
    public $watermark;
    public $watermarker;
    public $output;
	public $output_multiple;
	
	protected $_assets_directory;

    function setUp() {
		
		$this->_assets_directory = PACKAGE_DIRECTORY . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR;
		
        $this->watermark = new PDFWatermark( $this->_assets_directory . "star.png" );

        $this->output =  $this->_assets_directory . "test-output.pdf";
		$this->output_multiple =  $this->_assets_directory . "test-output-multiple.pdf";
		
		$input = $this->_assets_directory . "test.pdf";
		$input_multiple = $this->_assets_directory . "test-multipage.pdf";

        $this->watermarker = new PDFWatermarker( $input, $this->output, $this->watermark); 
		$this->watermarker_multiple = new PDFWatermarker( $input_multiple, $this->output_multiple, $this->watermark); 
		
    }
	
    public function testDefaultOptions() {
		
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . "output-default-position.pdf") === filesize($this->output) );

    }
	
    public function testDefaultOptionsWithJPG() {
		
		$watermark_jpg = new PDFWatermark( $this->_assets_directory . 'star.jpg');
		$watermarker_jpg = new PDFWatermarker( $this->_assets_directory . 'test.pdf', $this->output, $watermark_jpg); 
		
        $watermarker_jpg->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-from-jpg.pdf') === filesize($this->output) );

    }
	
    public function testTopRightPosition() {
		$this->watermark->setPosition('topright');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-topright-position.pdf') === filesize($this->output) );
    }
	
    public function testTopLeftPosition() {
		$this->watermark->setPosition('topleft');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-topleft-position.pdf') === filesize($this->output) );
    }
	
    public function testBottomRightPosition() {
		$this->watermark->setPosition('bottomright');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-bottomright-position.pdf') === filesize($this->output) );
    }
	
    public function testBottomLeftPosition() {
		$this->watermark->setPosition('bottomleft');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-bottomleft-position.pdf') === filesize($this->output) );
    }
	
    public function testAsBackground() {
		$this->watermark->setAsBackground();
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-as-background.pdf') === filesize($this->output) );
    }
	
	public function testSpecificPages() {
		$this->watermarker_multiple->setPageRange(3,5);
        $this->watermarker_multiple->savePdf(); 
        $this->assertTrue( file_exists($this->output_multiple) === true );
		$this->assertTrue( filesize( $this->_assets_directory . 'output-multipage.pdf') === filesize($this->output_multiple) );
    }
	
}