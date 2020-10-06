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
    function getUser($token, $profileId);
    function getMatches($token);
    function getCertainMatch($token, $id); // todo: check the response
    function getSpotifySettings($token); // todo; 404
    function getCommonConnections($token, $userId); // todo: 404
    function sendMessage($token, $userId, $message); // todo: 404

    function getActivityFeed($token);
    function getInstagramAuthorize($token);
    function getMetadatav1($token); //todo: do we need it?
    function getFastMatchPreview($token); // todo: returns null, is it ok ?
    function getFastMatchCount($token);
    function getTrendingGifs($token);
    function getSearchGifs($token);
}

