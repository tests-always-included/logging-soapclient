<?php

namespace TestsAlwaysIncluded\LoggingSoapClient;

use Psr\Log\LoggerInterface;
use \SoapClient as BaseSoapClient;
use \DOMDocument;
use \DOMXPath;

class SoapClient extends BaseSoapClient
{
    protected $logger;

    /**
     * Overrides __doRequest to allow logging
     * the request and response
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->logXML($action, $request);
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        if(0 == $one_way)
        {
            $this->logXML($action, $response);
        }
        return $response;
    }

    /**
     * Sets the new endpoint location
     *
     * @param string $newLocation
     */
    public function __setLocation($newLocation)
    {
        $this->location = $newLocation;
    }

    /**
     * Returns the endpoint location
     *
     * @return string
     */
    public function __getLocation()
    {
        return $this->location;
    }

    /**
     * Log the XML
     *
     * @param string $action
     * @param string $xml
     */
    protected function logXML($action, $xml)
    {
        $this->getLogger()->info($action, array('xml' => $xml));
    }

    /**
     * Sets the PSR Logger class
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns the Logger class
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
