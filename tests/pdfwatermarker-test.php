<?php

require_once("../fpdf/fpdf.php");
require_once("../fpdi/fpdi.php");
require_once("../pdfwatermarker/pdfwatermarker.php");
require_once("../pdfwatermarker/pdfwatermark.php");

class PDFWatermarker_test extends PHPUnit_Framework_TestCase
{
    public $watermark;
    public $watermarker;
    public $output;

    function setUp() {
        $this->watermark = new PDFWatermark('../assets/star.png');

        $this->output = tempnam( sys_get_temp_dir(), uniqid().".pdf");

        $this->watermarker = new PDFWatermarker('../assets/test.pdf', $this->output, $this->watermark); 
    }

    public function testwatermarkPdf()
    {
        $this->watermarker->setWatermarkPosition('bottomleft');
        $this->watermarker->watermarkPdf(); 
        $this->assertTrue( file_exists($this->output) === true );
    }
}