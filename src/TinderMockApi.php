<?php

namespace Henshall;

use GuzzleHttp\Client;
use Henshall\TinderApiInterface;

class TinderMockApi implements TinderApiInterface
{
    const URL = 'https://api.gotinder.com';
    
    /**
    * @var ClientInterface|Client
    */
    private $client;
    
    private $responses;
    
    public function __construct()
    {      
        $this->responses = include('Responses.php');
        $this->client = new Client;
    }
    
    /**
    * @return string
    */
    public function requestCode($phoneNumber)
    {
        return $this->responses["requestCode"];
    }
    
    /**
    * @return object
    */
    public function validateCode($phoneNumber, $code)
    {
        return $this->responses["validateCode"];
    }
    
    /**
    * @return array
    */
    public function getTokenFromRefreshToken($token)
    {
        return $this->responses["getTokenFromRefreshToken"];
    }
    
    /**
    * Get user profile.
    *
    * @param string $token
    *
    * @return array
    */
    public function getProfile($token)
    {
        return $this->responses["getProfile"];
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
        return $this->responses["getTokenFromRefreshToken"];
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
        return $this->responses["swipe_right_success"];
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
        return $this->responses["swipe_left_success"];
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
        return $this->responses["getMetadata"];
    }
    
    /**
    * Updates user location.
    *
    * @param string $token Tinder access token
    * @param array $position array (lat => float, lng => float)
    *
    * @return array
    */
    public function ping($token, array $position)
    {
        return $this->responses["ping"];
    }
}
