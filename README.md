[![Build Status](https://travis-ci.org/binarystash/pdf-watermarker.svg?branch=master)](https://travis-ci.org/binarystash/pdf-watermarker)

# PDF Watermarker
PDFWatermarker enables you to add an image as a watermark to existing PDF files. It uses FPDF that allows you to write PDF files and FPDI that allows you to import existing PDF documents into FPDF.

Using it, you can:

* Use jpg and png ( with alpha channels ) files with a 96 DPI resolution
* Easily position the watermark on the pages of the PDF file

## Installation

1. Place [composer.phar](https://getcomposer.org/composer.phar) in the folder you extracted the files to.

2. Run `php composer.phar install`

3. Include the files in your project. 

``` php
<?php
require_once('pdfwatermarker/pdfwatermarker.php');
require_once('pdfwatermarker/pdfwatermark.php');
```

## Usage

``` php
<?php

//Specify path to image. The image must have a 96 DPI resolution.
$watermark = new PDFWatermark('C:\myimage.png'); 

//Set the position
$watermark->setPosition('bottomleft');

//Place watermark behind original PDF content. Default behavior places it over the content.
$watermark->setAsBackground();

//Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
$watermarker = new PDFWatermarker('C:\test.pdf','C:\output.pdf',$watermark); 

//Set page range. Use 1-based index.
$watermarker->setPageRange(1,5);
 
//Save the new PDF to its specified location
$watermarker->savePdf(); 
?>
```

Five positions can be used. 'center' is the default.

* center
* topleft
* topright
* bottomright
* bottomleft

See the [API Documentation](https://github.com/binarystash/pdf-watermarker/wiki/API-Documentation) for more details.

## Support

Report bugs at https://github.com/binarystash/pdf-watermarker/issues.

