<?php

namespace Dimmask\Finetrack\Adapter;

use Dimmask\Finetrack\TrackResult;

/**
 * Interface AdapterInterface
 * @package Dimmask\Finetrack\Adapter
 */
interface AdapterInterface
{
    /**
     * Track ship
     *
     * @param $barcode
     * @param array $options
     *
     * @return TrackResult
     */
    public function track($barcode, $options = []): TrackResult;
}