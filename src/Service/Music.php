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
        $content = [];
        $client = HttpClient::create();
        $oauth_signature=$this->sign_request($request, $this->secret_key, null, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/oauth/request_token',
            [
                'query' => [
                    'oauth_consumer_key' => $this->key,
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent(); // get the response in XML format

            // XML to JSON
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $tokens = json_decode($json,TRUE);
            
            $tokensSignature = array(
                'tokens' => $tokens,
                'oauth_signature' => $oauth_signature,
            );
            return $tokensSignature;
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }

    public function getArtist(String $name, String $name2)
    {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = 'http://api.music-story.com/artist/search?name=' . $name . '&oauth_token=' . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauth_signature=$this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/artist/search',
            [
                'query' => [
                    'name' => $name2,
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent();
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $content = json_decode($json,TRUE);

            return $content['data']['item'];
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }

    function sign_request($request, $consumer_secret, $token_secret = null, $http_method = 'GET') {
        $a = explode('?', $request);
        $host_uri = $a[0];
        $params = isset($a[1])?$a[1]:null;
        $params = explode('&', $params);
        if(isset($params['oauth_signature'])) unset($params['oauth_signature']);
        sort($params);
        ksort($params);
        $encoded_parameters = implode('&',$params);
    
        $base = str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($http_method)));
        $base.= '&';
        $base.= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($host_uri)));
        $base.= '&';
        $base.= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($encoded_parameters)));
    
        $hmac_key = str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($consumer_secret)));
        $hmac_key.= '&';
        $hmac_key.= str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($token_secret)));
    
        $oauth_signature = base64_encode(hash_hmac('sha1', $base, $hmac_key, true));
    
        return $oauth_signature;
    }

    public function getPicture(String $artistId)
    {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = 'http://api.music-story.com/fr/artist/' . $artistId . '/pictures?oauth_token=' . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauth_signature=$this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/fr/artist/' . $artistId . '/pictures',
            [
                'query' => [
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent();
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $content = json_decode($json,TRUE);

            return $content['data']['item'];
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }

    public function getAlbums(String $artistId)
    {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = 'http://api.music-story.com/fr/artist/' . $artistId . '/albums?oauth_token=' . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauth_signature=$this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/fr/artist/' . $artistId . '/albums',
            [
                'query' => [
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent();
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $content = json_decode($json,TRUE);

            return $content['data']['item'];
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }

    public function getTracks(String $artistId)
    {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = 'http://api.music-story.com/fr/artist/' . $artistId . '/tracks?oauth_token=' . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauth_signature=$this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/fr/artist/' . $artistId . '/tracks',
            [
                'query' => [
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent();
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $content = json_decode($json,TRUE);

            return $content['data']['item'];
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }



    public function getArtistById(String $artistId)
    {
        $tokensSignature = $this->getTokens();
        $token = $tokensSignature['tokens']['data']['token'];
        $request = 'http://api.music-story.com/fr/artist/' . $artistId . '?oauth_token=' . $token;
        $client = HttpClient::create();
        $secretToken = $tokensSignature['tokens']['data']['token_secret'];
        $oauth_signature=$this->sign_request($request, $this->secret_key, $secretToken, 'GET');
        $response = $client->request(
            'GET',
            'http://api.music-story.com/fr/artist/' . $artistId,
            [
                'query' => [
                    'oauth_token' => $tokensSignature['tokens']['data']['token'],
                    'oauth_signature' => $oauth_signature,
                ]
            ]
        );
        $statusCode = $response->getStatusCode(); // get Response status code 200
        if ($statusCode === Music::STATUS) {
            $xmlContent = $response->getContent();
            $xml = simplexml_load_string($xmlContent);
            $json = json_encode($xml);
            $content = json_decode($json,TRUE);

            return $content;
        }
        throw new RuntimeException('Le service est temporairement indisponible.');
    }
}
