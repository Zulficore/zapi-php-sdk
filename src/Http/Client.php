<?php

declare(strict_types=1);

namespace ZAPI\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;
use ZAPI\Exceptions\RateLimitException;
use ZAPI\Exceptions\ServerException;

/**
 * HTTP Client - ZAPI API istekleri için HTTP client wrapper
 * 
 * Bu sınıf ZAPI API'sine HTTP istekleri göndermek için kullanılır.
 * PSR-18 standartlarına uygun olarak tasarlanmıştır ve Guzzle HTTP
 * client'ını kullanır.
 * 
 * @package ZAPI\Http
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $client = new Client('https://api.example.com', 'api_key', 'app_id');
 * $response = $client->sendRequest($request);
 */
class Client implements ClientInterface
{
    /**
     * HTTP client instance
     */
    private ClientInterface $httpClient;
    
    /**
     * Request factory
     */
    private RequestFactoryInterface $requestFactory;
    
    /**
     * Stream factory
     */
    private StreamFactoryInterface $streamFactory;
    
    /**
     * API base URL
     */
    private string $baseUrl;
    
    /**
     * API anahtarı
     */
    private string $apiKey;
    
    /**
     * Bearer token
     */
    private ?string $bearerToken;
    
    /**
     * Uygulama ID'si
     */
    private string $appId;
    
    /**
     * Timeout süresi
     */
    private int $timeout;
    
    /**
     * Debug modu
     */
    private bool $debug;
    
    /**
     * Default headers
     */
    private array $defaultHeaders;
    
    /**
     * Client constructor
     * 
     * @param string $baseUrl API base URL
     * @param string $apiKey API anahtarı
     * @param string $appId Uygulama ID'si
     * @param int $timeout Timeout süresi
     * @param bool $debug Debug modu
     * @param string|null $bearerToken Bearer token
     * 
     * @throws ZAPIException Geçersiz parametreler verilirse
     */
    public function __construct(
        string $baseUrl,
        string $apiKey,
        string $appId,
        int $timeout = 30,
        bool $debug = false,
        ?string $bearerToken = null
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->appId = $appId;
        $this->timeout = $timeout;
        $this->debug = $debug;
        $this->bearerToken = $bearerToken;
        
        // HTTP factory'leri başlat
        $this->requestFactory = new HttpFactory();
        $this->streamFactory = new HttpFactory();
        
        // Guzzle client'ı başlat
        $this->httpClient = new GuzzleClient([
            'timeout' => $this->timeout,
            'connect_timeout' => 10,
            'verify' => true,
            'http_errors' => false,
            'debug' => $this->debug
        ]);
        
        // Default headers
        $this->defaultHeaders = [
            'User-Agent' => 'ZAPI-PHP-SDK/' . \ZAPI\ZAPI::getVersion(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'x-api-key' => $this->apiKey,
            'x-app-id' => $this->appId
        ];
        
        // Bearer token varsa Authorization header'ına ekle
        if ($this->bearerToken) {
            $this->defaultHeaders['Authorization'] = 'Bearer ' . $this->bearerToken;
        }
    }
    
    /**
     * HTTP isteği gönderir
     * 
     * @param RequestInterface $request HTTP isteği
     * @return ResponseInterface HTTP yanıtı
     * 
     * @throws AuthenticationException Kimlik doğrulama hatası
     * @throws ValidationException Doğrulama hatası
     * @throws RateLimitException Rate limit hatası
     * @throws ServerException Sunucu hatası
     * @throws ZAPIException Genel API hatası
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            // Headers ekle
            $request = $this->addDefaultHeaders($request);
            
            // URL'i tamamla
            $request = $this->completeUrl($request);
            
            // İsteği gönder
            $response = $this->httpClient->sendRequest($request);
            
            // Yanıtı kontrol et
            $this->handleResponse($response);
            
            return $response;
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new ZAPIException('Bağlantı hatası: ' . $e->getMessage(), 0, null, 0);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new ZAPIException('İstek hatası: ' . $e->getMessage(), 0, null, 0);
        } catch (\Exception $e) {
            throw new ZAPIException('Beklenmeyen hata: ' . $e->getMessage(), 0, null, 0);
        }
    }
    
    /**
     * GET isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $query Query parametreleri
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->get('/user/profile');
     * $data = $client->get('/user/responses', ['page' => 1, 'limit' => 10]);
     */
    public function get(string $endpoint, array $query = [], array $headers = []): array
    {
        $url = $this->buildUrl($endpoint, $query);
        $request = $this->requestFactory->createRequest('GET', $url);
        
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        $response = $this->sendRequest($request);
        return $this->parseResponse($response);
    }
    
    /**
     * POST isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $data Gönderilecek veri
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->post('/auth/login', ['email' => 'user@example.com', 'password' => 'password']);
     */
    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->buildUrl($endpoint);
        $body = $this->streamFactory->createStream(json_encode($data));
        $request = $this->requestFactory->createRequest('POST', $url)->withBody($body);
        
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        $response = $this->sendRequest($request);
        return $this->parseResponse($response);
    }
    
    /**
     * PUT isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $data Gönderilecek veri
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->put('/user/profile', ['firstName' => 'Yeni Ad']);
     */
    public function put(string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->buildUrl($endpoint);
        $body = $this->streamFactory->createStream(json_encode($data));
        $request = $this->requestFactory->createRequest('PUT', $url)->withBody($body);
        
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        $response = $this->sendRequest($request);
        return $this->parseResponse($response);
    }
    
    /**
     * PATCH isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $data Gönderilecek veri
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->patch('/user/profile', ['firstName' => 'Yeni Ad']);
     */
    public function patch(string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->buildUrl($endpoint);
        $body = $this->streamFactory->createStream(json_encode($data));
        $request = $this->requestFactory->createRequest('PATCH', $url)->withBody($body);
        
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        $response = $this->sendRequest($request);
        return $this->parseResponse($response);
    }
    
    /**
     * DELETE isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->delete('/user/responses/123');
     */
    public function delete(string $endpoint, array $headers = []): array
    {
        $url = $this->buildUrl($endpoint);
        $request = $this->requestFactory->createRequest('DELETE', $url);
        
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        $response = $this->sendRequest($request);
        return $this->parseResponse($response);
    }
    
    /**
     * Multipart form data ile POST isteği gönderir
     * 
     * @param string $endpoint Endpoint path
     * @param array $data Form data
     * @param array $files Dosya verileri
     * @param array $headers Ek headers
     * @return array Yanıt verisi
     * 
     * @example
     * $data = $client->postMultipart('/user/avatar', [], ['avatar' => '/path/to/file.jpg']);
     */
    public function postMultipart(string $endpoint, array $data = [], array $files = [], array $headers = []): array
    {
        $url = $this->buildUrl($endpoint);
        
        // Multipart form data oluştur
        $multipart = [];
        
        foreach ($data as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value
            ];
        }
        
        foreach ($files as $key => $file) {
            if (is_string($file)) {
                // Dosya yolu
                $multipart[] = [
                    'name' => $key,
                    'contents' => fopen($file, 'r'),
                    'filename' => basename($file)
                ];
            } elseif (is_array($file)) {
                // Dosya verisi
                $multipart[] = [
                    'name' => $key,
                    'contents' => $file['contents'],
                    'filename' => $file['filename'] ?? 'file'
                ];
            }
        }
        
        // Guzzle ile multipart isteği gönder
        $response = $this->httpClient->request('POST', $url, [
            'multipart' => $multipart,
            'headers' => array_merge($this->defaultHeaders, $headers)
        ]);
        
        return $this->parseResponse($response);
    }
    
    /**
     * URL oluşturur
     * 
     * @param string $endpoint Endpoint path
     * @param array $query Query parametreleri
     * @return string Tam URL
     */
    private function buildUrl(string $endpoint, array $query = []): string
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        
        return $url;
    }
    
    /**
     * Default headers ekler
     * 
     * @param RequestInterface $request HTTP isteği
     * @return RequestInterface Güncellenmiş istek
     */
    private function addDefaultHeaders(RequestInterface $request): RequestInterface
    {
        foreach ($this->defaultHeaders as $name => $value) {
            if (!$request->hasHeader($name)) {
                $request = $request->withHeader($name, $value);
            }
        }
        
        return $request;
    }
    
    /**
     * URL'i tamamlar
     * 
     * @param RequestInterface $request HTTP isteği
     * @return RequestInterface Güncellenmiş istek
     */
    private function completeUrl(RequestInterface $request): RequestInterface
    {
        $uri = $request->getUri();
        
        if (empty($uri->getHost())) {
            $uri = $uri->withScheme('https')
                      ->withHost(parse_url($this->baseUrl, PHP_URL_HOST))
                      ->withPort(parse_url($this->baseUrl, PHP_URL_PORT) ?? 443);
        }
        
        return $request->withUri($uri);
    }
    
    /**
     * HTTP yanıtını kontrol eder
     * 
     * @param ResponseInterface $response HTTP yanıtı
     * @return void
     * 
     * @throws AuthenticationException 401 hatası
     * @throws ValidationException 400 hatası
     * @throws RateLimitException 429 hatası
     * @throws ServerException 5xx hatası
     * @throws ZAPIException Diğer hatalar
     */
    private function handleResponse(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        
        if ($statusCode >= 200 && $statusCode < 300) {
            return; // Başarılı yanıt
        }
        
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        
        $message = $data['message'] ?? $data['error'] ?? 'Bilinmeyen hata';
        $code = $data['code'] ?? $statusCode;
        
        switch ($statusCode) {
            case 400:
                throw new ValidationException($message, $code, is_array($data) ? $data : [], $statusCode);
            case 401:
                throw new AuthenticationException($message, $code, is_array($data) ? $data : [], $statusCode);
            case 429:
                throw new RateLimitException($message, $code, is_array($data) ? $data : [], $statusCode);
            case 500:
            case 502:
            case 503:
            case 504:
                throw new ServerException($message, $code, is_array($data) ? $data : [], $statusCode);
            default:
                throw new ZAPIException($message, $code, is_array($data) ? $data : [], $statusCode);
        }
    }
    
    /**
     * HTTP yanıtını parse eder
     * 
     * @param ResponseInterface $response HTTP yanıtı
     * @return array Parse edilmiş veri
     * 
     * @throws ZAPIException JSON parse hatası
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        
        if (empty($body)) {
            return [];
        }
        
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ZAPIException('JSON parse hatası: ' . json_last_error_msg());
        }
        
        return $data;
    }
    
    /**
     * API anahtarını günceller
     * 
     * @param string $apiKey Yeni API anahtarı
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        $this->defaultHeaders['x-api-key'] = $apiKey;
        return $this;
    }
    
    /**
     * Bearer token'ı günceller
     * 
     * @param string $bearerToken Yeni Bearer token
     * @return self
     */
    public function setBearerToken(string $bearerToken): self
    {
        $this->bearerToken = $bearerToken;
        $this->defaultHeaders['Authorization'] = 'Bearer ' . $bearerToken;
        return $this;
    }
    
    /**
     * Uygulama ID'sini günceller
     * 
     * @param string $appId Yeni uygulama ID'si
     * @return self
     */
    public function setAppId(string $appId): self
    {
        $this->appId = $appId;
        $this->defaultHeaders['x-app-id'] = $appId;
        return $this;
    }
    
    /**
     * Base URL'i günceller
     * 
     * @param string $baseUrl Yeni base URL
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }
    
    /**
     * Timeout süresini günceller
     * 
     * @param int $timeout Yeni timeout süresi
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        $this->httpClient = new GuzzleClient([
            'timeout' => $this->timeout,
            'connect_timeout' => 10,
            'verify' => true,
            'http_errors' => false,
            'debug' => $this->debug
        ]);
        return $this;
    }
    
    /**
     * Debug modunu günceller
     * 
     * @param bool $debug Debug modu
     * @return self
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        $this->httpClient = new GuzzleClient([
            'timeout' => $this->timeout,
            'connect_timeout' => 10,
            'verify' => true,
            'http_errors' => false,
            'debug' => $this->debug
        ]);
        return $this;
    }
}
