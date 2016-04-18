<?php

namespace Conferenceplus\Collmex;

use \MarcusJaschen\Collmex\Client\Curl as CurlClient;
use \MarcusJaschen\Collmex\Request;
use \MarcusJaschen\Collmex\Type\Customer;
use \MarcusJaschen\Collmex\Type\Invoice;

/**
 * Class Base
 * @package  Conferenceplus\Collmex
 * @since   1.0
 */
class Base
{
    protected $config;

    protected $client;

    protected $request;

    /**
     * Base constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $user     = $this->config['cpconf']->get('collmexuser');
        $pass     = $this->config['cpconf']->get('collmexpass');
        $clientId = $this->config['cpconf']->get('collmexclientnumber');

        // initialize HTTP client
        $this->client = new CurlClient($user, $pass, $clientId);

        // create request object
        $this->request = new Request($this->client);
    }
}
