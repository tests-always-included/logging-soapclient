<?php

namespace TestsAlwaysIncluded\LoggingSoapClient;

use Psr\Log\LoggerInterface;
use \SoapClient as BaseSoapClient;
use \DOMDocument;
use \DOMXPath;

class SoapClient extends BaseSoapClient
{
    protected $logger;

    public function __construct($wsdl, array $options = array())
    {
        //Set trace to true, or we cannot log the XML
        $options['trace'] = true;
        parent::SoapClient($wsdl, $options);
    }

    /**
     * Overrides __doRequest to allow logging
     * the request and response
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->logXML($action, $request, $this->__getLocation());
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        if(0 == $one_way)
        {
            $this->logXML($action . ':Response', $response);
        }
        return $response;
    }

    /**
     * Sets the new endpoint location
     *
     * @param string $newLocation
     */
    public function __setLocation($newLocation = null)
    {
        if(false === is_null($newLocation))
        {
            $this->location = $newLocation;
        }
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
    protected function logXML($action, $xml, $location = null)
    {
        $context = array(
            'xml' => $xml,
        );
        if(false === is_null($location))
        {
            $context['location'] = $location;
        }
        $this->getLogger()->info($action, $context);
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
