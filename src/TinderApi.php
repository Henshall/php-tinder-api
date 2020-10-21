<?php

namespace Henshall;

use GuzzleHttp\Client;

class TinderApi implements TinderApiInterface
{
    const URL = 'https://api.gotinder.com';
    const LIMIT = 5;
    const QUERY = 'a';
    const UK = '44';
    const VE = '58';
    const IE = '353';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client;
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

        return $this->makeGetRequest($token, '/profile');
    }

    /**
     * Get profiles recommendations.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getRecommendations($token)
    {

        return $this->makeGetRequest($token, '/v2/recs/core');
    }

    /**
     * Swipe to right.
     *
     * @param string $token
     *
     * @param string $id
     *
     * @return mixed
     */
    public function like($token, $id)
    {

        return $this->makeGetRequest($token, '/like/' . $id);
    }


    /**
     * Swipe left.
     *
     * @param string $token
     *
     * @param string $id
     *
     * @return mixed
     */
    public function pass($token, $id)
    {

        return $this->makeGetRequest($token, '/pass/' . $id);
    }


    /**
     * GETS YOUR OWN METADATA.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getMetadata($token)
    {

        return $this->makeGetRequest($token, '/v2/meta');
    }

    /**
     * GETS YOUR OWN METADATA.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getMetadatav1($token)
    {

        return $this->makeGetRequest($token, '/meta');
    }


    /**
     * Updates users location.
     *
     * @param string $token Tinder access token
     * @param array $position array (lat => float, lng => float)
     *
     * @return array
     */
    public function ping($token, array $position)
    {
        $data = $this->makeGetRequest($token, '/user/ping');

        if (array_key_exists('error', $data)) {
            throw new \RuntimeException('You can`t change your location frequently. Please try again later.');
        }

        return $data;
    }


    /**
     * Gets Token based upon users Refresh Token
     *
     * @param string $token Tinder access token
     *
     * @return array
     */
    public function getTokenFromRefreshToken($token)
    {
        $responseArray = $this->makePostRequest($token, '/v2/auth/login/sms');

        return $responseArray['data'];
    }

    /**
     * Sends SMS message to user to verify their account
     *
     * @param string $phoneNumber Your phone number associated with your tinder account
     *
     * @throws
     *
     * @return string
     */
    public function validateCode($phoneNumber, $code)
    {
        $headers = [
            'Authority' => 'api.gotinder.com',
            'Origin' => 'https://tinder.com',
            'X-Recovery-Token' => ' ',
            'X-Auth-Token' => ' ',
            'User-Session-Time-Elapsed' => '109054',
            'X-Supported-Image-Formats' => 'webp,jpeg',
            'Content-Type' => 'application/json',
            'User-Session-Id' => 'null',
            'Accept' => 'application/json',
            'Platform' => 'web',
            'Sec-Fetch-Site' => 'cross-site',
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
            'Sec-Fetch-Mode' => 'cors',
            'Referer' => 'https://tinder.com/',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'en-US,en;q=0.9,fr;q=0.8',
        ];

        $data = ['otp_code' => $code, 'phone_number' => $phoneNumber, 'is_update' => false];

        try {
            $response = $this->client->post(self::URL . '/v2/auth/sms/validate?auth_type=sms&locale=en', [
                'json' => $data,
                'headers' => $headers
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response->getBody()->getContents();
    }

    /**
     * Sends SMS message to user to verify their account
     *
     * @param string $phoneNumber Your phone number associated with your tinder account
     *
     * @throws
     *
     * @return string
     */
    public function requestCode($phoneNumber)
    {
        $number_plus = $phoneNumber;
        $number = ltrim($number_plus, '+');
        $number = preg_replace("/[^0-9]/", "", $number);
        $code = $this->getCode($number);
        $headers = [
            "Authority" => " api.gotinder.com",
            "Pragma" => " no-cache",
            "Cache-Control" => " no-cache",
            "X-Supported-Image-Formats" => " webp,jpeg",
            "Funnel-Session-Id" => " bb041b36638ca491",
            "Persistent-Device-Id" => " 7dd5826b-8433-4543-bb4b-c9937e8c4205",
            "Tinder-Version" => " 2.43.0",
            "User-Agent" => " Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1",
            "Content-Type" => " application/x-google-protobuf",
            "User-Session-Id" => " null",
            "Accept" => " application/json",
            "App-Session-Time-Elapsed" => " 14852",
            "X-Auth-Token" => " ",
            "User-Session-Time-Elapsed" => " null",
            "Platform" => " web",
            "App-Session-Id" => " 93c554f5-f39c-4cf7-845c-4989cb321e1b",
            "App-Version" => " 1024300",
            "Origin" => " https",
            "Sec-Fetch-Site" => " cross-site",
            "Sec-Fetch-Mode" => " cors",
            "Sec-Fetch-Dest" => " empty",
            "Referer" => " https",
            "Accept-Language" => " en-US,en;q=0.9,fr;q=0.8"
        ];

        try {
            $response = $this->client->post(self::URL . '/v3/auth/login?locale=en', [
                'body' =>  $code.$number,
                'headers' => $headers
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param string $token
     * @param string $profileId
     *
     * @return mixed
     */
    public function getUser($token, $profileId)
    {

        return $this->makeGetRequest($token, '/user/' . $profileId);
    }


    /**
     * Get matches profiles.
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getMatches($token)
    {

        return $this->makeGetRequest($token, '/v2/matches?count=50');
    }

    /**
     * Get certain matched profile by id.
     *
     * @param string $token
     *
     * @param string $matchId
     *
     * @return mixed
     */
    public function getCertainMatch($token, $matchId)
    {

        return $this->makeGetRequest($token, "/matches/{$matchId}");
    }

    /**
     * Get common connection of a user
     *
     * @param string $token
     *
     * @param string $userId
     *
     * @return mixed
     */
    public function getCommonConnections($token, $userId)
    {

        return $this->makeGetRequest($token, "/user/{$userId}/common_connections");
    }

    /**
     * Get Spotify settings
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getSpotifySettings($token)
    {

        return $this->makeGetRequest($token, '/v2/profile/spotify');
    }

    /**
     * Send Message to that id
     *
     * @param string $token
     *
     * @param string $userId
     *
     * @param string $message
     *
     * @throws
     *
     * @return array
     */
    public function sendMessage($token, $userId, $message)
    {
        //$userId = '5f44702123434afe8d';
        $response = $this->client->post(self::URL . "/user/matches/{$userId}/", [
            'json' => ['message' => $message],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get activity feed, including old and updated bios for comparison
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getActivityFeed($token)
    {

        return $this->makeGetRequest($token, '/v1/activity/feed?direction=past&eventTypes=1023');
    }

    /**
     * Auth Instagram
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getInstagramAuthorize($token)
    {

        return $this->makeGetRequest($token, '/instagram/authorize');
    }

    /**
     * Get the non blurred thumbnail image shown in the messages-window (the one showing the likes you received)
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getFastMatchPreview($token)
    {

        return $this->makeGetRequest($token, '/v2/fast-match/preview');
    }

    /**
     * Get the number of likes you received
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getFastMatchCount($token)
    {

        return $this->makeGetRequest($token, '/v2/fast-match/count');
    }

    /**
     * Get the trending gifs (tinder uses giphy) accessible in chat
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getTrendingGifs($token)
    {

        return $this->makeGetRequest($token, '/giphy/trending?limit=' . self::LIMIT);
    }


    /**
     * Get gifs (tinder uses giphy) based on a search accessible in chat
     *
     * @param string $token
     *
     * @return mixed
     */
    public function getSearchGifs($token)
    {

        return $this->makeGetRequest($token, '/giphy/search?limit=' . self::LIMIT . '&query=' . self::QUERY);
    }


    /**
     * Common method to make some get requests
     *
     * @param string $token
     *
     * @param string $url
     *
     * @param string $method
     *
     * @throws
     *
     * @return array
     */
    private function makeGetRequest($token, $url)
    {
        $response = $this->client->get(self::URL . $url, [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * Common method to make some post requests
     *
     * @param string $token
     *
     * @param string $url
     *
     * @throws
     *
     * @return array
     */
    private function makePostRequest($token, $url)
    {
        $response = $this->client->post(self::URL . $url, [
            'json' => [
                'refresh_token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);

    }

    /**
     * @param $number
     * @return string
     */
    private function getCode ($number)
    {
        // for UK AND IE  numbers
        if(
            strpos($number, self::UK) === 0
            || strpos($number, self::IE) === 0
            || strpos($number, self::VE) === 0
        ) {
            return chr(10) . chr(14) . chr(10) . chr(12);
        }
        return chr(10) . chr(13) . chr(10) . chr(11);
    }
}
