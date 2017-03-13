<?php

namespace Dimmask\Finetrack;

/**
 * Class TrackResult
 * @package Dimmask\Finetrack
 */
class TrackResult
{
    /**
     * @var bool    Flag if shipment was found
     */
    public $found = false;

    /**
     * @var string  Original barcode
     */
    public $barcode = '';

    /**
     * @var int Search result status
     */
    public $status_code = 0;

    /**
     * @var string  Status message
     */
    public $status_message = '';

    /**
     * @var Additional information
     */
    public $additional = [];

}