<?php

namespace App\Service;

use LogicException;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;

class Music
{
    private string $key;
    private string $secret_key;
    private const STATUS = 200;

    public function __construct(string $key, string $secret_key)
    {
        $this->key = $key;
        $this->secret_key = $secret_key;
    }

    public function getTokens()
    {
        $request = 'http://api.music-story.com/oauth/request_token?oauth_consumer_key=' . $this->key;
        $oauthSignature = $this->sign_request($request, $this->secret_key, null, 'GET');
        $response = HttpClient::create()->request('GET', 'http://api.music-story.com/oauth/request_token', [
            'query' => [
                'oauth_consumer_key' => $this->key,
                'oauth_signature' => $oauthSignature,
            ]
        ]);
        if ($response->getStatusCode() === Music::STATUS) {
            $tokens = $this->xmlToJson($response);
            $tokensSignature = array('tokens' => $tokens, 'oauth_signature' => $oauthSignature);
            return $tokensSignature;
        }
        throw new RuntimeException('The service is temporarily unavailable.');
    }

    function sign_request($request, $consumerSecret, $tokenSecret = null, $httpMethod = 'GET')
    {
        $splitedRequest = explode('?', $request);
        $hostUri = $splitedRequest[0];
        $params = isset($splitedRequest[1])?$splitedRequest[1]:null;
        $params = explode('&', $params);
        if(isset($params['oauth_signature'])) unset($params['oauth_signature']);
        sort($params);
        ksort($params);
        $encodedParameters = implode('&',$params);
        $base = str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($httpMethod)));
        $base .= '&';
        $base .= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($hostUri)));
        $base .= '&';
        $base .= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($encodedParameters)));
        $hmacKey = str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($consumerSecret)));
        $hmacKey .= '&';
        $hmacKey .= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($tokenSecret)));
        $oauthSignature = base64_encode(hash_hmac('sha1', $base, $hmacKey, true));
        return $oauthSignature;
    }

    public function getArtist(String $name)
    {
        $encodedName = str_replace(" ", "%20", $name);
        $apiUrl = 'http://api.music-story.com/artist/search';
        $apiUrlParameters = '?name=' . $encodedName . '&oauth_token=';
        $response = $this->apiRequest($name, $apiUrl, $apiUrlParameters);
        if ($response->getStatusCode() === Music::STATUS) {
            $content = $this->xmlToJson($response);
            return $content['data']['item'];
        }
        throw new RuntimeException('The service is temporarily unavailable.');
    }

    public function getMusicData(String $artistId, String $requestParameter)
    {
        $apiUrl = 'http://api.music-story.com/fr/artist/' . $artistId . $requestParameter;
        $apiUrlParameters = '?oauth_token=';
        $response = $this->apiRequest('', $apiUrl, $apiUrlParameters);
        if ($response->getStatusCode() === Music::STATUS) {
            return $this->xmlToJson($response);
        }
        throw new RuntimeException('The service is temporarily unavailable.');
    }

    public function xmlToJson(object $response) {
        $xmlContent = $response->getContent(); // Get the response in XML format
        $xml = simplexml_load_string($xmlContent);
        $json = json_encode($xml);
        $content = json_decode($json,TRUE);
        return $content;
    }
  
    public function apiRequest(String $name, String $apiUrl, String $apiUrlParameters) {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = $apiUrl . $apiUrlParameters . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauthSignature = $this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        if ($name != '') {
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'name' => $name,
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauthSignature,
                ]
            ]);
        } else {
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauthSignature,
                ]
            ]);
        }
        return $response;
    }
}
