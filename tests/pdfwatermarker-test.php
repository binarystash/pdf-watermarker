<?php

$parent_directory = dirname(__FILE__);

require_once($parent_directory."/../vendor/binarystash/fpdf/fpdf.php");
require_once($parent_directory."/../vendor/setasign/fpdi/fpdi.php");
require_once($parent_directory."/../pdfwatermarker/pdfwatermarker.php");
require_once($parent_directory."/../pdfwatermarker/pdfwatermark.php");

class PDFWatermarker_test extends PHPUnit_Framework_TestCase
{
    public $watermark;
    public $watermarker;
    public $output;
	public $output_multiple;
	public $parent_directory;

    function setUp() {
		
		$this->parent_directory = dirname(__FILE__);
		
        $this->watermark = new PDFWatermark($this->parent_directory.'/../assets/star.png');

        $this->output = $this->parent_directory ."/../assets/test-output.pdf";
		$this->output_multiple = $this->parent_directory ."/../assets/test-output-multiple.pdf";

        $this->watermarker = new PDFWatermarker($this->parent_directory.'/../assets/test.pdf', $this->output, $this->watermark); 
		$this->watermarker_multiple = new PDFWatermarker($this->parent_directory.'/../assets/test-multipage.pdf', $this->output_multiple, $this->watermark); 
		
    }
	
    public function testDefaultOptions() {
		
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-default-position.pdf') === filesize($this->output) );

    }
	
    public function testDefaultOptionsWithJPG() {
		
		$watermark_jpg = new PDFWatermark($this->parent_directory.'/../assets/star.jpg');
		$watermarker_jpg = new PDFWatermarker($this->parent_directory.'/../assets/test.pdf', $this->output, $watermark_jpg); 
		
        $watermarker_jpg->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-from-jpg.pdf') === filesize($this->output) );

    }
	
    public function testTopRightPosition() {
		$this->watermark->setPosition('topright');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-topright-position.pdf') === filesize($this->output) );
    }
	
    public function testTopLeftPosition() {
		$this->watermark->setPosition('topleft');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-topleft-position.pdf') === filesize($this->output) );
    }
	
    public function testBottomRightPosition() {
		$this->watermark->setPosition('bottomright');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-bottomright-position.pdf') === filesize($this->output) );
    }
	
    public function testBottomLeftPosition() {
		$this->watermark->setPosition('bottomleft');
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-bottomleft-position.pdf') === filesize($this->output) );
    }
	
    public function testAsBackground() {
		$this->watermark->setAsBackground();
        $this->watermarker->savePdf(); 
        $this->assertTrue( file_exists($this->output) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-as-background.pdf') === filesize($this->output) );
    }
	
	public function testSpecificPages() {
		$this->watermarker_multiple->setPageRange(3,5);
        $this->watermarker_multiple->savePdf(); 
        $this->assertTrue( file_exists($this->output_multiple) === true );
		$this->assertTrue( filesize($this->parent_directory.'/../assets/output-multipage.pdf') === filesize($this->output_multiple) );
    }
	
}