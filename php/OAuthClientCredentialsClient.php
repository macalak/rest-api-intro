<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class OAuthClientCredentialsClient
{
    private string $tokenUrl;
    private string $clientId;
    private string $clientSecret;
    private FilesystemAdapter $cache;
    private Client $apiClient;

    public function __construct(string $tokenUrl, string $clientId, string $clientSecret)
    {
        $this->tokenUrl = $tokenUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        
        // Inicializácia súborovej cache (PSR-16 / PSR-6 kompatibilná)
        $this->cache = new FilesystemAdapter('oauth_tokens', 3600);
        
        // Vytvorenie HandlerStacku pre interceptor (Middleware)
        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            // Interceptor: Automaticky získa platný token a pridá ho do Authorization hlavičky
            $token = $this->getValidAccessToken();
            return $request->withHeader('Authorization', 'Bearer ' . $token);
        }));

        // Klientská inštancia, ktorú budete používať na API volania
        $this->apiClient = new Client(['handler' => $stack]);
    }

    /**
     * Získa token z cache, alebo požiada o nový, ak starý expiruje.
     */
    private function getValidAccessToken(): string
    {
        $cacheKey = 'access_token_' . md5($this->clientId);
        $cachedToken = $this->cache->getItem($cacheKey);

        if ($cachedToken->isHit()) {
            return $cachedToken->get();
        }

        // Ak token v cache nie je alebo vypršal, požiadame o nový pomocou Client Credentials
        return $this->fetchNewAccessToken($cachedToken);
    }

    /**
     * Vykoná autentifikačné volanie na OAuth server.
     */
    private function fetchNewAccessToken($cacheItem): string
    {
        $authClient = new Client();
        
        $response = $authClient->post($this->tokenUrl, [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['access_token'])) {
            throw new \RuntimeException('Nepodarilo sa získať Access Token z OAuth servera.');
        }

        $accessToken = $data['access_token'];
        
        // Zistenie expirácie (predvolene 3600s ak chýba) s rezervou 60 sekúnd na sieťové oneskorenie
        $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] : 3600;
        $safetyBuffer = 60; 
        $ttl = max(1, $expiresIn - $safetyBuffer);

        // Uloženie prístupového tokenu do cache na určenú dobu vypršania
        $cacheItem->set($accessToken);
        $cacheItem->expiresAfter($ttl);
        $this->cache->save($cacheItem);

        return $accessToken;
    }

    /**
     * Wrapper pre bežné API volania
     */
    public function request(string $method, string $uri, array $options = [])
    {
        return $this->apiClient->request($method, $uri, $options);
    }
}

// === PRÍKLAD POUŽITIA ===

$oauthClient = new OAuthClientCredentialsClient(
    'https://auth.preprod.parking.scheidt-bachmann.net/auth/realms/de_studentui/protocol/openid-connect/token', // OAuth Token Endpoint
    'B2C_de_studentui',                     // Client ID
    'RDDUE3OcCyBAZDSe6nABRtpEt4itgcVX'                  // Client Secret
);

// Parking API GET Facilities test
try {
    // Volanie externej API. Hlavička "Authorization: Bearer <token>" sa pridá úplne sama.
    $response = $oauthClient->request('GET',
 	          'https://pm.preprod.parking.scheidt-bachmann.net/capacity-manager/v2/de_studentui/occupancy/facilities',
	          [
                'headers' => [
                'Accept' => 'application/json',
              ]
        ]);
	$rawJson = $response->getBody()->getContents();
	echo $rawJson;
    // Prevedenie textu na PHP pole (alebo objekt)
    $data = json_decode($rawJson, true);
    // Spätné zakódovanie s formátovaním "PRETTY_PRINT"
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} catch (\Exception $e) {
    echo "Chyba pri API volaní: " . $e->getMessage();
}
