<?php

namespace Dimmask\Finetrack\Adapter;

use Dimmask\Finetrack\Exception\TrackException;
use Dimmask\Finetrack\TrackResult;

/**
 * Class Ukrposhta
 * Ukrposhta API adapter class
 *
 * @package Dimmask\Finetrack\Adapter
 */
class Ukrposhta implements AdapterInterface
{
    /**
     * @var string  API key
     */
    protected $guid = 'fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd';

    /**
     * @var string  Default response text language ["uk" or "en"]
     */
    public $culture = 'en';

    /**
     * @var string  API port
     */
    public $api_port = 'http://services.ukrposhta.ua/barcodestatistic/barcodestatistic.asmx?WSDL';

    /**
     * Soap client
     *
     * @var null
     */
    protected $soap = null;

    /**
     * Ukrposhta adapter constructor.
     */
    public function __construct($culture = 'en', $key = null)
    {
        $this->guid = $key ?? $this->guid;
        $this->culture = $culture;
        $this->soap = new \SoapClient($this->api_port);
    }

    /**
     * @inheritdoc
     */
    public function track($barcode, $options = []): TrackResult
    {
        $data           = new \stdClass();
        $data->guid     = $this->guid;
        $data->barcode  = $barcode;
        $data->culture  = $this->culture;

        try{
            $raw = $this->soap->GetBarcodeInfo($data)->GetBarcodeInfoResult;
        } catch (\Exception $e) {
            throw new TrackException($e->getMessage(), $e->getCode());
        }

        // Prepare result info:
        $result                 = new TrackResult();
        $result->barcode        = $raw->barcode;
        $result->status_code    = (int)$raw->code;
        $result->status_message = $raw->eventdescription;
        $result->found          = !empty($result->status_code);

        // Prepare additional info:
        $result->additional = [
            'lastofficeindex' => $raw->lastofficeindex,
            'lastoffice' => $raw->lastoffice,
            'eventdate' => $raw->eventdate ?? null
        ];

        return $result;
    }
}