<?php
use Tygh\Registry;
require_once 'BSG/SmsApiClient.php';
require_once 'BSG/HLRApiClient.php';
require_once 'BSG/ViberApiClient.php';

class BSG
{
    private $apiKey;
    private $sender;
    private $tariff;
    private $viberSender;

    public function __construct($sender = null, $viberSender = null, $tariff = null, $mode = 'live') {
        $apiKey = $mode == 'live' ? Registry::get('addons.csc_bsg_world.api_key') : Registry::get('addons.csc_bsg_world.test_api_key');
        $this->apiKey = $apiKey;
        $this->sender = $sender;
        $this->tariff = $tariff;
        $this->viberSender = $viberSender;
    }

    /**
     * @return SmsApiClient
     */
    public function getSmsClient() {
        return new SmsApiClient($this->apiKey, $this->sender, $this->tariff);
    }

    /**
     * @return HLRApiClient
     */
    public function getHLRClient() {
        return new HLRApiClient($this->apiKey, $this->tariff);
    }

    /**
     * @return ViberApiClient
     */
    public function getViberClient() {
        return new ViberApiClient($this->apiKey, $this->viberSender);
    }

}