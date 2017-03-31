<?php
class MyCustomStorage implements OAuth2\Storage\AccessTokenInterface,
OAuth2\Storage\ClientCredentialsInterface
{
    public function checkClientCredentials($client_id, $client_secret = null) {
        
    }

    public function checkRestrictedGrantType($client_id, $grant_type) {
        
    }

    public function getAccessToken($oauth_token) {
        
    }

    public function getClientDetails($client_id) {
        
    }

    public function getClientScope($client_id) {
        
    }

    public function isPublicClient($client_id) {
        
    }

    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null) {
        
    }

// method implementation here...
}