<?php
/*
The MIT License (MIT)

Copyright (c) 2012 BinaryStash

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class PDFWatermarker {
	
	private $_originalPdf;
	private $_newPdf;
	private $_tempPdf;
	private $_watermark;
	
	public function __construct($originalPdf,$newPdf,$watermark) {
		
		$this->_originalPdf = $originalPdf;
		$this->_newPdf = $newPdf;
		$this->_tempPdf = new FPDI();
		$this->_watermark = $watermark;
		
		$this->_validateAssets();
	}

	
	/**
	 * Ensures that the watermark and the PDF file are valid
	 *
	 * @return void
	 */
	private function _validateAssets() {
		
		if ( !file_exists( $this->_originalPdf ) ) {
			throw new Exception("Inputted PDF file doesn't exist");
		}
		else if ( !file_exists( $this->_watermark->getFilePath() ) ) {
			throw new Exception("Watermark doesn't exist.");
		}
		
	}
	
	/**
	 * Loop through the pages while applying the watermark
	 *
	 * @return void
	 */
	private function _updatePDF() {
		$pageCtr = $this->_tempPdf->setSourceFile($this->_originalPdf);
		for($ctr = 1; $ctr <= $pageCtr; $ctr++){
			$this->_watermarkPage($ctr);
		}
	}
	
	/**
	 * Apply the watermark to each page on the PDF file
	 *
	 * @param int $page_number - page number
	 *
	 * @return void
	 */
	private function _watermarkPage($page_number) {
		$templateId = $this->_tempPdf->importPage($page_number);
		$templateDimension = $this->_tempPdf->getTemplateSize($templateId);
		
		if ( $templateDimension['w'] > $templateDimension['h'] ) {
			$orientation = "L";
		}
		else {
			$orientation = "P";
		}

	        $this->_tempPdf->DefOrientation = $orientation;

		$this->_tempPdf->addPage($orientation,array($templateDimension['w'],$templateDimension['h']));
		
		$wWidth = ($this->_watermark->getWidth() / 96) * 25.4; //in mm
		$wHeight = ($this->_watermark->getHeight() / 96) * 25.4; //in mm
		
		$watermarkCoords = $this->_calculateWatermarkCoordinates( 	$wWidth, 
																	$wHeight, 
																	$templateDimension['w'], 
																	$templateDimension['h']);
																	
		if ( $this->_watermark->usedAsBackground() ) {															
			$this->_tempPdf->Image($this->_watermark->getFilePath(),$watermarkCoords[0],$watermarkCoords[1],-96);
			$this->_tempPdf->useTemplate($templateId);
		}
		else {
			$this->_tempPdf->useTemplate($templateId);
			$this->_tempPdf->Image($this->_watermark->getFilePath(),$watermarkCoords[0],$watermarkCoords[1],-96);
		}
	}
	
	/**
	 * Calculate the coordinates of the watermark's position 
	 *
	 * @param int $wWidth - watermark's width
	 * @param int $wHeight - watermark's height
	 * @param int $tWidth - page width
	 * @param int $Height -page height
	 *
	 * @return array - coordinates of the watermark's position
	 */
	private function _calculateWatermarkCoordinates( $wWidth, $wHeight, $tWidth, $tHeight ) {
		
		switch( $this->_watermark->getPosition() ) {
			case 'topleft': 
				$x = 0;
				$y = 0;
				break;
			case 'topright':
				$x = $tWidth - $wWidth;
				$y = 0;
				break;
			case 'bottomright':
				$x = $tWidth - $wWidth;
				$y = $tHeight - $wHeight;
				break;
			case 'bottomleft':
				$x = 0;
				$y = $tHeight - $wHeight;
				break;
			default:
				$x = ( $tWidth - $wWidth ) / 2 ;
				$y = ( $tHeight - $wHeight ) / 2 ;
				break;
		}
		
		return array($x,$y);
	}
	
	/**
	 * Save the PDF to the specified location
	 *
	 * @return void
	 */
	public function savePdf() {
		$this->_updatePDF();
		$this->_tempPdf->Output($this->_newPdf);
	}
}
?>
