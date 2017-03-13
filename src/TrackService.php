<?php

namespace Dimmask\Finetrack;

use Dimmask\Finetrack\Adapter\AdapterInterface;
use Dimmask\Finetrack\Exception\InvalidTrackAdapterException;

/**
 * Class TrackService
 * @package Dimmask\Finetrack
 */
class TrackService
{
    const ADAPTER_NAMESPACE = '\\Dimmask\\Finetrack\\Adapter\\';

    /**
     * @var array   Service configuration
     */
    protected $config = [];

    /**
     * TrackService constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Make api adapter instance
     *
     * @param $name
     * @param array Configuration settings for adapter to be overriden
     *
     * @return AdapterInterface
     * @throws InvalidTrackAdapterException
     */
    public function makeAdapter($name, $cfg_override = []): AdapterInterface
    {
        $adapter_class = self::ADAPTER_NAMESPACE . ucfirst($name);
        if(class_exists($adapter_class)){
            $reflection_class = new \ReflectionClass($adapter_class);
            if($reflection_class->implementsInterface(self::ADAPTER_NAMESPACE . 'AdapterInterface')){
                $args = array_merge($this->config[$name] ?? array(), $cfg_override);
                $adapter = $reflection_class->newInstanceArgs($args);
            } else {
                throw new InvalidTrackAdapterException(sprintf('Class [%s] should implement AdapterInterface', $adapter_class));
            }
        } else {
            throw new InvalidTrackAdapterException(sprintf('[%s] class not found.', $adapter_class));
        }

        return $adapter;
    }

    /**
     * Track shipment with specified provider
     *
     * @param $adapter
     * @param $barcode
     * @param $params
     *
     * @return TrackResult
     */
    public function trackWith($adapter, $barcode, $params = []): TrackResult
    {
        return $this->makeAdapter($adapter)->track($barcode, $params);
    }

    /**
     * Track shipment using each of registered providers
     *
     * @param string
     * @param array
     *
     * @return array
     */
    public function track($barcode, $params = []): array
    {
        $results = [];
        $providers = $this->config['providers'] ?? [];
        if(!empty($providers)){
            foreach($providers as $provider => $settings){
                $result = $this->makeAdapter($provider)->track($barcode, $params);
                if($result->found){
                    $results[] = $result;
                }
            }
        }

        return $results;
    }
}