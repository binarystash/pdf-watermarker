<?php

namespace BinaryStash\PdfWatermarker;

interface PdfWatermarker
{
    /**
     * Set page range.
     *
     * @param int $startPage - the first page to be watermarked
     * @param int|null $endPage - (optional) the last page to be watermarked
     */
    public function setPageRange($start, $end = null);

    /**
     * Set the Position of the Watermark
     *
     * @param Position $position
     * @return void
     */
    public function setPosition(Position $position);

    /**
     * Set the watermark as background.
     *
     * @return void
     */
    public function setAsBackground();

    /**
     * Set the watermark as overlay.
     *
     * @return void
     */
    public function setAsOverlay();

    /**
     * Save the PDF.
     *
     * @param $file
     * @return void
     */
    public function savePdf($file);
}
