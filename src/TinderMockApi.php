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
        return $this->responses["getMetaData"];
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

    function getUser($token, $profileId)
    {
        return $this->responses["getUser"];
    }

    function getMatches($token)
    {
        return $this->responses["getMatches"];
    }

    function getCertainMatch($token, $id)
    {
        return $this->responses["getCertainMatch"];
    }

    function getSpotifySettings($token)
    {
        // TODO: Implement getSpotifySettings() method.
    }

    function getCommonConnections($token, $userId)
    {
        // TODO: Implement getCommonConnections() method.
    }

    function sendMessage($token, $userId, $message)
    {
        // TODO: Implement sendMessage() method.
    }

    function getActivityFeed($token)
    {
        return $this->responses["getActivityFeed"];
    }

    function getInstagramAuthorize($token)
    {
        return $this->responses["getInstagramAuthorize"];
    }

    function getMetadatav1($token)
    {
        return $this->responses["getMetadataV1"];
    }

    function getFastMatchPreview($token)
    {
        // TODO: Implement getFastMatchPreview() method.
    }

    function getFastMatchCount($token)
    {
        return $this->responses["getFastMatchCount"];
    }

    function getTrendingGifs($token)
    {
        return $this->responses["getTrendingGifs"];
    }

    function getSearchGifs($token)
    {
        return $this->responses["getSearchGifs"];
    }
}
