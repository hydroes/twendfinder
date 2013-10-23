<?php

//namespace Vendors\Twitter\Client;
class TwitterClient {
    private $host = 'https://api.twitter.com/1.1/';
    private $consumerKey;
    private $consumerSecret;
    private $oauthToken;
    private $oauthTokenSecret;

    public function __construct($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret) {
        $this->consumerKey = $consumer_key;
        $this->consumerSecret = $consumer_secret;
        $this->oauthToken = $oauth_token;
        $this->oauthTokenSecret = $oauth_token_secret;
    }

    protected static function request($method, $url, $params, $headers) {
        if($headers===null) $headers = array();
        if($params===null) $params = array();
        $method = mb_strtoupper($method);
        $contextHttp = array(
            'method' => $method,
        );
        switch($method) {
            case 'GET':
                if($params) {
                    $url .= '?'.http_build_query($params);
                }
                break;
            case 'POST':
            case 'PUT':
                $content = $params ? http_build_query($params) : '';
                $contextHttp['content'] = $content;
                $headers['Content-Length'] = strlen($content);
                break;
            default:
                throw new Exception('Unhandled method');
        }
        $headerSb = array();
        foreach($headers as $k => $v) {
            $headerSb[] = "$k: $v";
        }
        $contextHttp['header'] = implode("\r\n", $headerSb) . "\r\n";
        $context = stream_context_create(array(
            'http' => $contextHttp
        ));
        return file_get_contents($url, false, $context);
    }

    protected function buildAuthHeader($method, $baseUrl, $reqParams) {
        $oauthParams = array(
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => uniqid(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->oauthToken,
            'oauth_version' => '1.0',
        );
        $encParams = array_merge($oauthParams, $reqParams);
        ksort($encParams);
        $paramSb = array();
        foreach($encParams as $k => $v) {
            $paramSb[] = rawurlencode($k) . '=' . rawurlencode($v);
        }
        $paramStr = implode('&', $paramSb);
        $sigBaseStr = $method . '&' . rawurlencode($baseUrl) . '&' . rawurlencode($paramStr);
        $signingKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->oauthTokenSecret);
        $oauthParams['oauth_signature'] = base64_encode(hash_hmac('sha1', $sigBaseStr, $signingKey, true));
        ksort($oauthParams);
        $authSb = array();
        foreach($oauthParams as $k => $v) {
            $authSb[] = rawurlencode($k) . '="' . rawurlencode($v) . '"';
        }
        return 'OAuth ' . implode(', ', $authSb);
    }

    protected function send($method, $resource, $params) {
        if(!preg_match('~\.\w+$~',$resource)) {
            $resource .= '.json';
        }
        $url = $this->host . ltrim($resource, '/');
        $headers = array(
            'Authorization' => $this->buildAuthHeader($method, $url, $params),
        );
        $response = self::request($method, $url, $params, $headers);
        if($response === false) throw new Exception('Bad request');
        return json_decode($response, true);
    }

    public function get($resource, $params) {
        return $this->send('GET', $resource, $params);
    }

    public function post($resource, $params) {
        return $this->send('POST', $resource, $params);
    }
}