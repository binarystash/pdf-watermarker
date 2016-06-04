<?php
/**
 * pdfwatermarker.php
 * 
 * This class applies PDFWatermark to the file
 * @author Binarystash <binarystash01@gmail.com>
 * @version 1.1.1
 * @license https://opensource.org/licenses/MIT MIT
 */

class PDFWatermarker {
	
	private $_originalPdf;
	private $_newPdf;
	private $_tempPdf;
	private $_watermark;
	private $_specificPages;
	
	/**
	 * Creates an instance of the watermarker
	 *
	 * @param string $originalPDF - inputted PDF path
	 * @param string $newPDF - outputted PDF path
	 * @param mixed $watermark Watermark - watermark object
	 *
	 * @return void
	 */
	public function __construct($originalPdf,$newPdf,$watermark) {
		
		$this->_originalPdf = $originalPdf;
		$this->_newPdf = $newPdf;
		$this->_tempPdf = new FPDI();
		$this->_watermark = $watermark;
		$this->_specificPages = array();
		
		
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
		
		$totalPages = $this->_getTotalPages();
		
		for($ctr = 1; $ctr <= $totalPages; $ctr++){
			
			$this->_importPage($ctr);
			
			if ( in_array($ctr, $this->_specificPages ) || empty( $this->_specificPages ) ) {
				$this->_watermarkPage($ctr);
			}
			else {
				$this->_watermarkPage($ctr, false);
			}
			
		}
		
	}
	
	/*
	 * Get total number of pages
	 *
	 * @return int 
	 */
	private function _getTotalPages() {
		return $this->_tempPdf->setSourceFile($this->_originalPdf);
	}
	
	/**
	 * Import page
	 *
	 * @param int $page_number - page number
	 *
	 * @return void
	 */
	private function _importPage($page_number) {
		
		$templateId = $this->_tempPdf->importPage($page_number);
		$templateDimension = $this->_tempPdf->getTemplateSize($templateId);
		
		if ( $templateDimension['w'] > $templateDimension['h'] ) {
			$orientation = "L";
		}
		else {
			$orientation = "P";
		}
		
		$this->_tempPdf->addPage($orientation,array($templateDimension['w'],$templateDimension['h']));
		
	}
	
	/**
	 * Apply the watermark to a specific page
	 *
	 * @param int $page_number - page number
	 * @param bool $watermark_visible - (optional) Make the watermark visible. True by default.
	 *
	 * @return void
	 */
	private function _watermarkPage($page_number, $watermark_visible = true) {
		
		$templateId = $this->_tempPdf->importPage($page_number);
		$templateDimension = $this->_tempPdf->getTemplateSize($templateId);
		
		$wWidth = ($this->_watermark->getWidth() / 96) * 25.4; //in mm
		$wHeight = ($this->_watermark->getHeight() / 96) * 25.4; //in mm
		
		$watermarkCoords = $this->_calculateWatermarkCoordinates( 	$wWidth, 
																	$wHeight, 
																	$templateDimension['w'], 
																	$templateDimension['h']);
							
		if ( $watermark_visible ) {
			if ( $this->_watermark->usedAsBackground() ) {															
				$this->_tempPdf->Image($this->_watermark->getFilePath(),$watermarkCoords[0],$watermarkCoords[1],-96);
				$this->_tempPdf->useTemplate($templateId);
			}
			else {
				$this->_tempPdf->useTemplate($templateId);
				$this->_tempPdf->Image($this->_watermark->getFilePath(),$watermarkCoords[0],$watermarkCoords[1],-96);
			}
		}
		else {
			$this->_tempPdf->useTemplate($templateId);
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
	 * Set page range
	 *
	 * @param int $startPage - the first page to be watermarked
	 * @param int $endPage - (optional) the last page to be watermarked
	 *
	 * @return void
	 */
	public function setPageRange($startPage=1, $endPage=null) {
		
		$end = $endPage !== null ? $endPage : $this->_getTotalPages();
		
		$this->_specificPages = array();
		
		for ($ctr = $startPage; $ctr <= $end; $ctr++ ) {
			$this->_specificPages[] = $ctr;
		}
		
	}
	 
	
	/**
	 * Save the PDF to the specified location
	 *
	 * @return void
	 */
	public function savePdf() {
		$this->_updatePDF();
		$this->_tempPdf->Output("F",$this->_newPdf);
	}
}
?>
