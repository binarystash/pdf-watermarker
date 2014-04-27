<?php
/*
The MIT License (MIT)

Copyright (c) 2012 BinaryStash

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class PDFWatermark {

	private $_file;
	private $_height;
	private $_width;

	function __construct($file) {

		$this->_file = $this->_prepareImage($file);
		$this->_getImageSize( $this->_file );
	}
	
	private function _prepareImage($file) {
		
		$imagetype = exif_imagetype( $file );
		$path =  sys_get_temp_dir() . '/' . uniqid() . '.png';
		
		switch( $imagetype ) {
			
			case IMAGETYPE_JPEG: 
				$image = imagecreatefromjpeg($file);
				imageinterlace($image,false);
				imagejpeg($image, $path);
				imagedestroy($image);
				break;
				
			case IMAGETYPE_PNG:
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
	
	private function _getImageSize($image) {
		$is = getimagesize($image);
		$this->_width = $is[0];
		$this->_height = $is[1];
	}
	
	public function getFilePath() {
		return $this->_file;
	}
	
	public function getHeight() {
		return $this->_height;
	}
	
	public function getWidth() {
		return $this->_width;
	}
}