<?php

namespace Dimmask\Finetrack;

/**
 * Class BarcodeHelper
 * Contains barcode parsing tools, could be useful
 */
class BarcodeHelper
{
    /**
     * Barcode recognize pattern
     */
    const BARCODE_MASK = '/[\w]{1,2}[\d]{9,11}[\w]{1,2}/i';

    /**
     * Get all barcodes in the text
     *
     * @param   string  source text
     *
     * @return array
     */
    public static function getAll($source){

        preg_match_all(self::BARCODE_MASK, $source, $matches);

        return $matches;
    }

    /**
     * Get only first encounter of barcode in the text
     *
     * @param   string  source text
     *
     * @return string
     */
    public static function getFirst($source){

        preg_match(self::BARCODE_MASK, $source, $matches);

        return @array_shift($matches);
    }
}