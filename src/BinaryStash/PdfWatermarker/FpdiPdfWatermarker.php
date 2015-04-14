<?php

namespace BinaryStash\PdfWatermarker;

use fpdi\FPDI;

class FpdiPdfWatermarker implements PdfWatermarker
{
    private $watermark;
    private $totalPages;
    private $specificPages = [];
    private $position;
    private $asBackground = false;

    public function __construct(Pdf $file, Watermark $watermark)
    {
        $this->fpfi = new FPDI();
        $this->totalPages = $this->fpfi->setSourceFile($file->getRealPath());
        $this->watermark = $watermark;
        $this->position = new Position('MiddleCenter');
    }

    /**
     * Set page range.
     *
     * @param int $startPage - the first page to be watermarked
     * @param int $endPage - (optional) the last page to be watermarked
     */
    public function setPageRange($startPage = 1, $endPage = null)
    {
        $endPage = is_null($endPage) ? $this->totalPages : $endPage;

        foreach (range($startPage, $endPage) as $pageNumber) {
            $this->specificPages[] = $pageNumber;
        }
    }

    /**
     * Apply the watermark below the PDF's content.
     */
    public function setAsBackground()
    {
        $this->asBackground = true;
    }

    /**
     * Apply the watermark over the PDF's content.
     */
    public function setAsOverlay()
    {
        $this->asBackground = false;
    }

    /**
     * Set the Position of the Watermark
     *
     * @param Position $position
     */
    public function setPosition(Position $position)
    {
        $this->position = $position;
    }

    /**
     * Loop through the pages while applying the watermark.
     */
    private function process()
    {
        foreach (range(1, $this->totalPages) as $pageNumber) {
            $this->importPage($pageNumber);

            if (in_array($pageNumber, $this->specificPages) || empty($this->specificPages)) {
                $this->watermarkPage($pageNumber);
            } else {
                $this->watermarkPage($pageNumber, false);
            }
        }
    }

    /**
     * Import page.
     *
     * @param int $pageNumber - page number
     */
    private function importPage($pageNumber)
    {
        $templateId = $this->fpfi->importPage($pageNumber);
        $templateDimension = $this->fpfi->getTemplateSize($templateId);

        if ($templateDimension['w'] > $templateDimension['h']) {
            $orientation = "L";
        } else {
            $orientation = "P";
        }

        $this->fpfi->DefOrientation = $orientation;

        $this->fpfi->addPage($orientation, array($templateDimension['w'], $templateDimension['h']));
    }

    /**
     * Apply the watermark to a specific page.
     *
     * @param int $pageNumber - page number
     * @param bool $watermark_visible - (optional) Make the watermark visible. True by default.
     */
    private function watermarkPage($pageNumber, $watermark_visible = true)
    {
        $templateId = $this->fpfi->importPage($pageNumber);
        $templateDimension = $this->fpfi->getTemplateSize($templateId);

        $wWidth = ($this->watermark->getWidth() / 96) * 25.4; //in mm
        $wHeight = ($this->watermark->getHeight() / 96) * 25.4; //in mm

        $watermarkCoords = $this->calculateWatermarkCoordinates(
            $wWidth,
            $wHeight,
            $templateDimension['w'],
            $templateDimension['h']
        );

        if ($watermark_visible) {
            if ($this->asBackground) {
                $this->fpfi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -96);
                $this->fpfi->useTemplate($templateId);
            } else {
                $this->fpfi->useTemplate($templateId);
                $this->fpfi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -96);
            }
        } else {
            $this->fpfi->useTemplate($templateId);
        }
    }

    /**
     * Calculate the coordinates of the watermark's position.
     *
     * @param int $wWidth - watermark's width
     * @param int $wHeight - watermark's height
     * @param int $tWidth - page width
     * @param int $Height -page height
     *
     * @return array - coordinates of the watermark's position
     */
    private function calculateWatermarkCoordinates($wWidth, $wHeight, $tWidth, $tHeight)
    {
        switch ($this->position->getName()) {
            case 'TopLeft':
                $x = 0;
                $y = 0;
                break;
            case 'TopCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = 0;
                break;
            case 'TopRight':
                $x = $tWidth - $wWidth;
                $y = 0;
                break;
            case 'MiddleLeft':
                $x = 0;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'MiddleRight':
                $x = $tWidth - $wWidth;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'BottomLeft':
                $x = 0;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomRight':
                $x = $tWidth - $wWidth;
                $y = $tHeight - $wHeight;
                break;
            case 'MiddleCenter':
            default:
                $x = ($tWidth - $wWidth) / 2;
                $y = ($tHeight - $wHeight) / 2;
                break;
        }

        return array($x, $y);
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function savePdf($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpfi->Output($fileName, 'F');
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function downloadPdf($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpfi->Output($fileName, 'D');
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function stdOut($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpfi->Output($fileName, 'I');
    }
}
