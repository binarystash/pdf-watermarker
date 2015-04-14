# PDF Watermarker
PDFWatermarker enables you to add an image as a watermark to existing PDF files. It uses FPDF that allows you to write PDF files and FPDI that allows you to import existing PDF documents into FPDF.

Using it, you can:

* Use jpg and png ( with alpha channels ) files with a 96 DPI resolution
* Easily position the watermark on the pages of the PDF file

## Installation

Installing using composer

``` bash

> composer require binarystash/pdf-watermarker:^2.0

```

## Usage

``` php
<?php

use BinaryStash\PdfWatermarker\Pdf;
use BinaryStash\PdfWatermarker\Watermark;
use BinaryStash\PdfWatermarker\FpdiPdfWatermarker as PDFWatermarker;

// Specify path to the existing pdf
$pdf = new Pdf('my.pdf');

// Specify path to image. The image must have a 96 DPI resolution.
$watermark = new Watermark('watermark.png'); 

// Create a new watermarker
$watermarker = new PDFWatermarker($pdf, $watermark); 
 
// Save the new PDF to its specified location
$watermarker->savePdf('output.pdf');

?>
```

## Options

``` php
<?php

use BinaryStash\PdfWatermarker\Position;

// Set the position of the watermark
// All possible positions can be found in Position::options
$watermarker->setPosition(new Position('BottomCenter'));

// Alternatively
$watermarker->setPosition(Position::BottomCenter());

// Place watermark behind original PDF content. Default behavior places it over the content.
$watermarker->setAsBackground();

// Only Watermark specific range of pages
// This would only watermark page 3 and 4
$watermarker->setPageRange(3, 4);

?>
```

## Output Options

``` php
<?php

// The filename is optional for all output options
$watermarker->savePdf();

// Start a download of the PDF
$watermarker->downloadPdf('output.pdf');

// Send the PDF to standard out
$watermarker->stdOut('output.pdf');

?>
```

## Support

Report bugs at https://github.com/binarystash/pdf-watermarker/issues.
