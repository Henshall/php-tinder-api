<?php

namespace Henshall;

use GuzzleHttp\Client;

class TinderApi implements TinderApiInterface
{
    const URL = 'https://api.gotinder.com';

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
        $response = $this->client->get(self::URL . '/profile', [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
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
        $response = $this->client->get(self::URL . '/v2/recs/core', [
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
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
        $response = $this->client->get(self::URL . '/like/' . $id, [
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
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
        $response = $this->client->get(self::URL . '/pass/' . $id, [
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
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
        $response = $this->client->get(self::URL . '/v2/meta', [
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
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
        $response = $this->client->post(self::URL . '/user/ping', [
            'json' => $position,
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

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
        $response = $this->client->post(self::URL . '/v2/auth/login/sms', [
            'json' => [
                'refresh_token' => $token,
            ]
        ]);

        $responseArray = json_decode($response->getBody()->getContents(), true);

        return $responseArray['data'];
    }

    /**
     * Sends SMS message to user to verify their account
     *
     * @param string $phoneNumber Your phone number associated with your tinder account
     *
     * @return object
     *
     * @NOTE: for some reason this request needed to be done with curl. I could not get it to work with guzzle. Update for consistency.
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
        $responseArray = json_decode($response->getBody()->getContents(), true);

        return $responseArray['data'];
    }

    /**
     * Sends SMS message to user to verify their account
     *
     * @param string $phoneNumber Your phone number associated with your tinder account
     *
     * @return string
     *
     * @NOTE: for some reason this request needed to be done with curl. I could not get it to work with guzzle. Update for consistency.
     */
    public function requestCode($phoneNumber)
    {
        $number_plus = $phoneNumber;
        $number = ltrim($number_plus, '+');
        $number = preg_replace("/[^0-9]/", "", $number);

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
                'body' => chr(10) . chr(13) . chr(10) . chr(11) . $number,
                'headers' => $headers
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param $token
     * @param $profileId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUser($token, $profileId)
    {
        $url = self::URL . '/user/' . $profileId;
        $response = $this->client->get($url, [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    public function getMatches($token)
    {
        $response = $this->client->get(self::URL . '/v2/matches?count=50', [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);

    }

    public function getCertainMatch($token, $matchId)
    {
        $response = $this->client->get(self::URL . "/matches/{$matchId}", [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);

    }

    public function getCommonConnections($token, $userId)
    {
        $response = $this->client->get(self::URL . "/user/{$userId}/common_connections", [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getSpotifySettings($token)
    {
        $response = $this->client->get(self::URL . '/v2/profile/spotify', [
            'headers' => [
                'X-Auth-Token' => $token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);

    }

    public function sendMessage($token, $userId, $message)
    {
        $userId = '5f44702123434afe8d';
        $response = $this->client->post(self::URL . "/user/matches/{$userId}/", [
            'json' => ['message' => $message],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

}
