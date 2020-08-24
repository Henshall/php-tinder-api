<?php

namespace Henshall;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\Log;
use Henshall\TinderApiInterface;

class TinderMockApi implements TinderApiInterface
{
    const URL = 'https://api.gotinder.com';
    
    /**
    * @var ClientInterface|Client
    */
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
        
    }
    
    
    public function requestCode($phoneNumber)
    {
        return "ddd";
    }
    
    
    public function validateCode($phoneNumber, $loginRequestCode, $code)
    {
        
        return "ddd";
    }
    
    
    public function getTokenFromRefreshToken($token)
    {
        return "ddd";
    }
    
    /**
    * Get user profile.
    *
    * @param string $token
    *
    * @return array
    * @throws TransferException
    */
    public function getProfile($token)
    {
        return "ddd";
    }
    
    /**
    * Get profiles recommendations.
    *
    * @param string $token
    *
    * @return array
    */
    public function getRecommendations($token)
    {
        
        return "ddd";
    }
    
    /**
    * Swipe to right.
    *
    * @param string $token
    * @param string $id
    *
    * @return mixed
    */
    public function like($token, $id)
    {
        return config("testData.swipe_right_success");
    }
    
    
    
    /**
    * Swipe left.
    *
    * @param string $token
    * @param string $id
    *
    * @return mixed
    */
    public function pass($token, $id)
    {
        return config("testData.swipe_left_success");
    }
    
    
    /**
    * GETS YOUR OWN METADATA.
    *
    * @param string $token
    * @param string $id
    *
    * @return mixed
    */
    public function getMetadata($token)
    {
        return "ddd";
    }
    
    
    
    /**
    * Updates user location.
    *
    * @param string $token Tinder access token
    * @param array $position array (lat => float, lng => float)
    *
    * @return array
    * @throws \RuntimeException
    */
    public function ping($token, array $position)
    {
        
        return "ddd";
    }
}
