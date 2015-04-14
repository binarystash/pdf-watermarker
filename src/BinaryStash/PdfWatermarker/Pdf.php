<?php

namespace BinaryStash\PdfWatermarker;

class Pdf extends \SplFileObject
{
    private $file;

    public function __construct($file)
    {
        parent::__construct($file);

        $fileInfo = new \finfo();
        $mimeType = $fileInfo->file($file, FILEINFO_MIME_TYPE);

        if ($mimeType !== 'application/pdf') {
            throw new \InvalidArgumentException('File does not seem to be a PDF: ' . $mimeType);
        }

        $this->file = $file;
    }
}
