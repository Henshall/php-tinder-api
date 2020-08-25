<?php

namespace Henshall;

interface TinderApiInterface
{
    function requestCode($phoneNumber);
    function validateCode($phoneNumber, $code);
    function getTokenFromRefreshToken($token);
    function getRecommendations($token);
    function like($token, $id);
    function pass($token, $id);
    function getMetadata($task);
    function ping($token, array $position);
}

