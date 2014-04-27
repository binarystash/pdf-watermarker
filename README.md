[![Build Status](https://travis-ci.org/binarystash/pdf-watermarker.svg?branch=master)](https://travis-ci.org/binarystash/pdf-watermarker)

# PDF Watermarker
PDFWatermarker is a PHP class that enables you to add an image as a watermark to existing PDF files. It uses FPDF that allows you to write PDF files and FPDI that allows you to import existing PDF documents into FPDF.

Using this plugin, you can:

* Use jpg and png ( with alpha channels ) files as watermarks at a setting of 96 DPI
* Easily position the watermark on the pages of the PDF file

## Installation

Include the files in your project. 

``` php
<?php
require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');
require_once("pdfwatermarker/*");
```

## Usage

``` php
<?php

//Specify path to image
$watermark = new PDFWatermark('C:\myimage.png'); 
 
//Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
$watermarker = new PDFWatermarker('C:\test.pdf','C:\output.pdf',$watermark); 
 
//Set the position
$watermarker->setWatermarkPosition('bottomleft');
 
//Save the new PDF to its specified location
$watermarker->watermarkPdf(); 
?>
```

Five positions can be used. 'center' is the default.

* center
* topleft
* topright
* bottomright
* bottomleft

## Support

Report bugs at https://github.com/binarystash/pdf-watermarker/issues.

