<?php
/**
 * pdfwatermark.php
 * 
 * This class defines properties of a watermark
 * @author Binarystash <binarystash01@gmail.com>
 * @version 1.1.1
 * @license https://opensource.org/licenses/MIT MIT
 */

class PDFWatermark {

	private $_file;
	private $_height;
	private $_width;
	private $_position;
	private $_asBackground;
	
	/**
	 * Creates an instance of the watermark
	 *
	 * @param string $file - path to the image file
	 *
	 * @return void
	 */
	function __construct($file) {

		$this->_file = $this->_prepareImage($file);
		$this->_getImageSize( $this->_file );
		
		$this->_position = 'center';
		$this->_asBackground = false;
	}
	
	/**
	 * Configure or check the image's properties 
	 *
	 * @return void
	 */
	private function _prepareImage($file) {
		
		$imagetype = exif_imagetype( $file );
		
		switch( $imagetype ) {
			
			case IMAGETYPE_JPEG:
				$path =  sys_get_temp_dir() . '/' . uniqid() . '.jpg'; 
				$image = imagecreatefromjpeg($file);
				imageinterlace($image,false);
				imagejpeg($image, $path);
				imagedestroy($image);
				break;
				
			case IMAGETYPE_PNG:
				$path =  sys_get_temp_dir() . '/' . uniqid() . '.png';
				$image = imagecreatefrompng($file);
				imageinterlace($image,false);
				imagesavealpha($image,true);
				imagepng($image, $path);
				imagedestroy($image);
				break;
			default:
				throw new Exception("Unsupported image type");
				break;
		};
		
		return $path;
		
	}
	
	/**
	 * Assess the watermark's dimensions
	 *
	 * @return void
	 */
	private function _getImageSize($image) {
		$is = getimagesize($image);
		$this->_width = $is[0];
		$this->_height = $is[1];
	}
	
	/**
	 * Set the watermark's position
	 *
	 * @param string $position -  'center','topright', 'topleft', 'bottomright', 'bottomleft'
	 *
	 * @return void
	 */
	public function setPosition($position) {
		$this->_position = $position;
	}
	
	/**
	 * Apply the watermark below the PDF's content
	 *
	 * @return void
	 */
	public function setAsBackground() {
		$this->_asBackground = true;
	}
	
	/**
	 * Apply the watermark over the PDF's content
	 *
	 * @return void
	 */
	public function setAsOverlay() {
		$this->_asBackground = false;
	}
	
	/**
	 * Checks if the watermark is used as a background
	 *
	 * @return bool
	 */
	public function usedAsBackground() {
		return $this->_asBackground;
	}
	
	/**
	 * Returns the watermark's position
	 *
	 * @return string
	 */
	public function getPosition() {
		return $this->_position;
	}
	
	/**
	 * Returns the watermark's file path
	 *
	 * @return string
	 */
	public function getFilePath() {
		return $this->_file;
	}
	
	/**
	 * Returns the watermark's height
	 *
	 * @return int
	 */
	public function getHeight() {
		return $this->_height;
	}
	
	/**
	 * Returns the watermark's width
	 *
	 * @return int
	 */
	public function getWidth() {
		return $this->_width;
	}
}