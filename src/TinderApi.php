<?php 

namespace Henshall;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

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
        $response = $this->client->get(self::URL . '/like/'.$id, [
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
        $response = $this->client->get(self::URL . '/pass/'.$id, [
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
    *@NOTE: for some reason this request needed to be done with curl. I could not get it to work with guzzle. Update for consistency.
    */
    public function validateCode($phoneNumber, $code)
    {
    
        
        $ch = curl_init();    
        curl_setopt($ch, CURLOPT_URL, self::URL . '/v2/auth/sms/validate?auth_type=sms&locale=en');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"otp_code\":\"$code\",\"phone_number\":\"$phoneNumber\",\"is_update\":false}");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $headers = array();
        $headers[] = 'Authority: api.gotinder.com';
        $headers[] = 'Origin: https://tinder.com';
        $headers[] = 'X-Recovery-Token: ';
        $headers[] = 'X-Auth-Token: ';
        $headers[] = 'User-Session-Time-Elapsed: 109054';
        $headers[] = 'X-Supported-Image-Formats: webp,jpeg';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'User-Session-Id: null';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Platform: web';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Referer: https://tinder.com/';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: en-US,en;q=0.9,fr;q=0.8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    
    
    /**
    * Sends SMS message to user to verify their account
    *
    * @param string $phoneNumber Your phone number associated with your tinder account
    *
    * @return string
    *
    *@NOTE: for some reason this request needed to be done with curl. I could not get it to work with guzzle. Update for consistency.
    */
    public function requestCode($phoneNumber)
    {
        $number_plus = $phoneNumber;
        $number = ltrim($number_plus, '+'); 
        $number = preg_replace("/[^0-9]/", "", $number );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL . '/v3/auth/login?locale=en');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, chr(10).chr(13).chr(10).chr(11).$number);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        
        $headers = array();
        $headers[] = 'Authority: api.gotinder.com';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'X-Supported-Image-Formats: webp,jpeg';
        $headers[] = 'Tinder-Version: 2.43.0';
        $headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1';
        $headers[] = 'Content-Type: application/x-google-protobuf';
        $headers[] = 'User-Session-Id: null';
        $headers[] = 'Accept: application/json';
        $headers[] = 'App-Session-Time-Elapsed: 14852';
        $headers[] = 'X-Auth-Token: ';
        $headers[] = 'User-Session-Time-Elapsed: null';
        $headers[] = 'Platform: web';
        $headers[] = 'App-Version: 1024300';
        $headers[] = 'Origin: https://tinder.com';
        $headers[] = 'Sec-Fetch-Site: cross-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Referer: https://tinder.com/';
        $headers[] = 'Accept-Language: en-US,en;q=0.9,fr;q=0.8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = preg_replace("/[^0-9]/", "", $result );
        return $result;
    }
    
    
    

}
