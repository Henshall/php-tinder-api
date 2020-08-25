# PHP Tinder API
## Version: 1.0.0

This is a PHP package for Tinders (Match Group, Inc.) API. 

Tinders API is private - and there is not very much information on the internet about how to access or use it.
I have used chromes networking tab to examine Tinder xhr requests to create this API - it contains some methods found
no where else on the internet - including verion 3's sms authentication requests (new as of the last month of writing).

Additionally - you will find lots of examples as well as tips and trick, common mistakes while using, and more. 

### Classes in this Package:

Tinder API:
The Tinder API returns the json decoded results directly from the Tinder API without any modifications.
Some of the methods may need updating every once in a while, but I have found the have remained the same for the last few years. 

Tinder Mock Api:
You can use the 'Tinder Mock API' class for testing or developing since tinder has a strict limit on requests per hour. You don't want to be working with real data, so I have gone ahead and created this MockAPI with examples below on how to use it. 

Tinder Interface:
The interface ensures that we have the same methods in both classes, and you can inject this as a dependancy into your code to help decouple the API/MockAPI from your code. You can see some examples below.


## Installation with Composer:
```bash
composer require henshall/php-tinder-api
```

## Pre-requisites
- Create a Tinder Account (https://tinder.com/)



# Usage:


### Make sure to include the packages in your project:
```php
use Henshall\TinderApi;
use Henshall\TinderMockApi;
use Henshall\TinderApiInterface;
```

### Authentication Step 1 (Send an sms message to your phone for verification)
In this step, we will receive an sms message to our cell phone linked with our tinder account.
```php
$myTinderPhoneNumber = "1112223456"; // no spaces
$tinderApi = new TinderApi;
$result = $tinderApi->requestCode($myTinderPhoneNumber);
```

### Authentication Step 2 (Return the code to Tinder and specify the phone number that received it)
After receiving the code, we send it back to tinder and receive a refresh token. We will use this to
get our real token, which will expire after some time.
```php
$myTinderPhoneNumber = "1112223456"; // no spaces
$tinderApi = new TinderApi;
$result = $tinderApi->validateCode($myTinderPhoneNumber, "123456");
// Grab the Refresh Token
$refresh_token = $result->data["refresh_token"];
```

### Authentication Step 3 (Request a Tinder Token)
Here we can request our tinder token which we will use for the remainder of the method. If the tinder
token expires, you can simple generate a new one with the refresh token.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->getTokenFromRefreshToken($refresh_token);
// Grab the Refresh Token
$tinder_token = $result["api_token"];
```


### Get Profile
This gets the current users profile including bio, images, age, sex, and more.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->getProfile($tinder_token);
```

### Get MetaData
This gets the current users metadata which includes stuff like what a user will see in tinder banners, the users setttings, and more.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->getMetadata($tinder_token);
```

### Get Recommendations
This gives you a list of other users you can swipe on - and that are recommended to you
based on your profile settings. It returns 0-25 potential matches.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->getRecommendations($tinder_token);
```


### Like (Swipe Right)
This likes another user (aka swipes right on them). You can find the users id in the getRecommendations method above.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->like($tinder_token, $id);
```


### Pass (Swipe Left)
This passes another user (aka swipes right on them) and ignores them. You can find the users id in the getRecommendations method above.
```php
$tinderApi = new TinderApi;
$result = $tinderApi->pass($tinder_token, $id);
```


### Change Location 
A user can change their location using this method. Here we try to update the users swipe location. If it fails, we will simply return false instead of throwing an error.
```php
try {
    // Asign Variables
    $tinderApi = new TinderApi;
    $token = "1234567890qwerty";
    $position = ['lat' => "43.47876072885527", 'lon' => "-110.76437540803676"];
    // Try To Update Location
    echo "Attempting to update user Swipe Location\n";
    $update = $tinderApi->ping($token, $position);
    if (isset($update["status"]) && $update["status"] == 200) {
        echo "Location Change Successful! \n";
        return $update;
    } else {
        echo "Location Change Failed \n";
        return false; 
    }
} catch (\Exception $e) {
    echo "Location Change Failed \n";
    return false;
}
```



## Using The Tinder Mock API:
The Tinder Mock API will return successful mock responses for all of the same methods in the TinderApi. For example, you can see the example using the TinderMockApi's getProfile, is the example same as the TinderApi's.

```php
$tinderApi = new TinderMockApi;
$result = $tinderApi->getProfile($tinder_token);
```

## Using The TinderApiInterface
Both the TinderApi and the TinderMockApi implement the TinderApiInterface. This not only ensures they have the same methods, but also allows us to decouple our code for testing purposes. The following example is from a Laravel project where the interface is injected during construction. This is a "swiper" class which is responsible for using the Tinder API for swiping. 

```php

class Swiper 
{
    private $tinderApi;
    
    public function __construct(\App\Tinder\TinderApiInterface $tinderApi)
    {
        echo "SWIPER CREATED \n";
        $this->tinderApi = $tinderApi;
    }
    
    public function swipe($token, $id, $swipe_type){   
        if ($swipe_type == "like") {
            try {
                echo "swiping right \n";
                $response = $this->tinderApi->like($token, $id);
                
            } catch (\Exception $e) {
                echo "Failed to Swipe Right \n";
                return false;
            }
        } elseif ($swipe_type == "pass") {
            try {
                echo "swiping left \n";
                $response = $this->tinderApi->pass($token, $id);
            } catch (\Exception $e) {
                echo "Failed to Swipe Right \n";
                return false;
            }
        } else {
            echo "swipe_type is wrong - you should never see this \n";
            return null;
        }
        return $response;        
    }
}
```

In production we can use the swipe class as follows:
```php
$swiper = new Swiper(new TinderApi);
```

While testing, we can use the class as follows:
```php
$swiper = new Swiper(new TinderMockApi);
```


